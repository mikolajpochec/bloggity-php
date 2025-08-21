import { BlockToken } from './blockToken'
import { HeaderToken } from './headerToken'
import { ParagraphToken } from './paragraphToken'
import { QuoteToken } from './quoteToken'

export class MarkdownParser {
	private types;
	private markdown: string;

	constructor(markdown: string) {
		this.types = [HeaderToken, QuoteToken, ParagraphToken];
		this.markdown = markdown;
	}

	parse(): string {
		const lines: string[] = this.markdown.split('\n');
		let tokens: BlockToken[] = [];
		
		for(let line of lines) {
			const tokenType = this.types.find(t => t.match(line));
			if(!tokenType) continue;
			tokens.push(tokenType.fromLine(line));
		}

		// Merging
		for(let i = 1; i < tokens.length; i++) {
			let t1 = tokens[i - 1];
			let t2 = tokens[i];
			if(!this.compareTypes(t1,t2)) continue;
			if(t1.merge(t2)) {
				tokens.splice(i, 1);
				i--;
			}
		}

		let html: string = "";
		// Rendering
		for(let token of tokens) {
			html += token.renderHTML();
		}

		return html;
	}

	private compareTypes(t1: BlockToken, t2: BlockToken): boolean {
		return t1.constructor === t2.constructor;
	}
}
