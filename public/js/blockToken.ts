import { InlineRenderer } from './inlineRenderer'

export abstract class BlockToken {
	protected content: string;

	constructor(content: string) {
		this.content = content;
	}

	static match(line: string): boolean {
		return false;
	}
	static fromLine(line: string): BlockToken | null {
		return null;
	}

	/*
	 * For blocks which can extend to multiple lines.
	 *  If can merge, returns true and stores the result in itself.
	 */
	merge(next: BlockToken): boolean {
		return false;
	}
	renderHTML(): string {
		this.content = InlineRenderer.render(this.content);
	}

	get rawContent(): string { 
		return this.content;
	}
}
