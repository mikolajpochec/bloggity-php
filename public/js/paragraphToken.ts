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
		if(!next.content.trim() && !this.content.trim())
			return true;
		if(next.content.trim() && this.content.trim()) {
			let newContent = `${this.content}<br>${next.content}`;
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
