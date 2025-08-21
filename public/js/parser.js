// lib/utils/inlineRenderer.ts
var InlineRenderer = class {
	static render(text) {
		return text.replace(/`([^`]+)`/g, "<code>$1</code>").replace(/\*\*([^*]+)\*\*/g, "<strong>$1</strong>").replace(/\*([^*]+)\*/g, "<em>$1</em>").replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>');
	}
  };

  // lib/utils/blockToken.ts
  var BlockToken = class {
	content;
	constructor(content) {
	  this.content = content;
	}
	static match(line) {
	  return false;
	}
	static fromLine(line) {
	  return null;
	}
	/*
	 * For blocks which can extend to multiple lines.
	 *  If can merge, returns true and stores the result in itself.
	 */
	merge(next) {
	  return false;
	}
	renderHTML() {
	  this.content = InlineRenderer.render(this.content);
	  return "";
	}
	get rawContent() {
	  return this.content;
	}
  };

  // lib/utils/headerToken.ts
  var HeaderToken = class _HeaderToken extends BlockToken {
	depth;
	constructor(content, depth) {
	  super(content);
	  this.depth = depth;
	}
	static match(line) {
	  return /^(#{1,6})\s+/.test(line);
	}
	static fromLine(line) {
	  const [, hashes, content] = line.match(/^(#{1,6})\s+(.*)$/);
	  return new _HeaderToken(content, hashes.length);
	}
	renderHTML() {
	  return `<h${this.depth}>${this.content}</h${this.depth}>`;
	}
  };

  // lib/utils/paragraphToken.ts
  var ParagraphToken = class extends BlockToken {
	constructor(content) {
	  super(content);
	}
	static fromLine(line) {
	  return new this(line);
	}
	static match(line) {
	  return true;
	}
	merge(next) {
	  if (!next.rawContent.trim() && !this.rawContent.trim())
		return true;
	  if (next.rawContent.trim() && this.content.trim()) {
		let newContent = `${this.content}<br>${next.rawContent}`;
		this.content = newContent;
		return true;
	  }
	  return false;
	}
	renderHTML() {
	  super.renderHTML();
	  return `<p>${this.content.trim()}</p>`;
	}
  };

  // lib/utils/quoteToken.ts
  var QuoteToken = class extends BlockToken {
	constructor(content) {
	  super(content);
	}
	static match(line) {
	  return /^(>)(.*)$/.test(line);
	}
	static fromLine(line) {
	  const match = line.match(/^(>)(.*)$/);
	  if (!match) throw new Error(`Quote not found in line '${line}'.`);
	  let content = match[2];
	  return new this(content);
	}
	renderHTML() {
	  this.content = new MarkdownParser(this.content).parse();
	  this.content = this.content.replaceAll("\n", "<br>");
	  super.renderHTML();
	  return `<div class="article-quote">${this.content}</div>`;
	}
	merge(next) {
	  this.content += `
		${next.rawContent}`;
	  return true;
	}
  };

  // lib/utils/parser.ts
  var MarkdownParser = class {
	types;
	markdown;
	constructor(markdown) {
	  this.types = [HeaderToken, QuoteToken, ParagraphToken];
	  this.markdown = markdown;
	}
	parse() {
	  const lines = this.markdown.split("\n");
	  let tokens = [];
	  for (let line of lines) {
		const tokenType = this.types.find((t) => t.match(line));
		if (!tokenType) continue;
		tokens.push(tokenType.fromLine(line));
	  }
	  for (let i = 1; i < tokens.length; i++) {
		let t1 = tokens[i - 1];
		let t2 = tokens[i];
		if (!this.compareTypes(t1, t2)) continue;
		if (t1.merge(t2)) {
		  tokens.splice(i, 1);
		  i--;
		}
	  }
	  let html = "";
	  for (let token of tokens) {
		html += token.renderHTML();
	  }
	  return html;
	}
	compareTypes(t1, t2) {
	  return t1.constructor === t2.constructor;
	}
  };
