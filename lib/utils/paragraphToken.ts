import { BlockToken } from './blockToken'

export class ParagraphToken extends BlockToken {
	constructor(content) {
		super(content);
	}

	static fromLine(line) {
		return new this(line);
	}

	static match(line) {
		return true;
	}

	merge(next: BlockToken): boolean {
		if(!next.rawContent.trim() && !this.rawContent.trim())
			return true;
		if(next.rawContent.trim() && this.content.trim()) {
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
}
