export interface AuthUser {
    id: number
    name: string
    username: string
    email: string
}

export interface Flash {
    success?: string | null
    error?: string | null
}

export interface PageProps {
    auth: {
        user: AuthUser | null
    }
    flash: Flash
    errors: Record<string, string>
    [key: string]: unknown
}

export interface PaginationLinks {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
}

export interface PaginationMeta {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number | null
    to: number | null
}

export interface Paginated<T> {
    data: T[]
    links: PaginationLinks
    meta: PaginationMeta
}
