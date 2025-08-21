import { BlockToken } from './blockToken'

export class HeaderToken extends BlockToken {
	private depth: number;

	constructor(content: string, depth: number) {
		super(content);
		this.depth = depth;
	}

	static match(line: string): boolean {
		return /^(#{1,6})\s+/.test(line);
	}

	static fromLine(line: string): BlockToken {
		const [,hashes,content] = line.match(/^(#{1,6})\s+(.*)$/);
		return new HeaderToken(content, hashes.length);
	}
	
	renderHTML(): string {
		return `<h${this.depth}>${this.content}</h${this.depth}>`;
	}
}
