export interface SearchResponse {
    [name: string]: SearchResult[];
}

export interface SearchResult {
    searchable: any;
    title: string;
    url: string;
    type: string;
}
