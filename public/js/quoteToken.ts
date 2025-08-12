import { MarkdownParser } from "./parser";
import { BlockToken } from './blockToken'

export class QuoteToken extends BlockToken {
	constructor(content: string) {
		super(content);
	}

	static match(line: string): boolean {
		return /^(>)(.*)$/.test(line);
	}

	static fromLine(line: string): BlockToken {
		const match = line.match(/^(>)(.*)$/);
		if (!match) throw new Error(`Quote not found in line '${line}'.`);
		let content = match[2];
		return new this(content);
	}

	renderHTML(): string {
		this.content = (new MarkdownParser(this.content)).parse();
		this.content = this.content.replaceAll('\n', '<br>');
		super.renderHTML();
		return `<div class="article-quote">${this.content}</div>`;
	}

	merge(next: BlockToken): boolean {
		this.content += `\n${next.rawContent}`;
		return true;
	}
}
