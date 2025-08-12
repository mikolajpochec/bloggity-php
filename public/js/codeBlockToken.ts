import { BlockToken } from './blockToken'

export abstract class CodeBlockToken {
	language: string | null = null;
	static match(line: string): boolean {
		return /^```/.test(line))
	}
	static fromLine(line: string): BlockToken {
		const match = line.match(/^```(\w*)/);
		const language = match ? match[1] : null;
		this.content = match[0];
	}
	merge(next: BlockToken): boolean {
	}
}
