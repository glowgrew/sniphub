export interface Category {
    id: number;
    name: string;
}

export interface CategoryResponse {
    data: Category[];
}

// type SnippetStoreResponse = {
//     id: number;
//     title: string;
//     body: string;
//     category: number;
//     burn_after_read: boolean;
//     expiration_time: Date;
//     created_at: Date;
//     password: string;
//     is_public: boolean;
// }