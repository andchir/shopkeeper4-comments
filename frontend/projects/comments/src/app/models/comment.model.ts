export class Comment {
    constructor(
        public id: number,
        public threadId?: string,
        public vote?: number,
        public status?: string,
        public createdTime?: string,
        public publishedTime?: string,
        public comment?: string,
        public reply?: string
    ) {
        if (typeof this.status === 'undefined') {
            this.status = 'pending';
        }
        if (typeof this.vote === 'undefined') {
            this.vote = 1;
        }
    }
}
