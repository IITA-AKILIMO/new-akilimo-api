export interface User {
    id: number
    name: string
    username: string
    email: string
    created_at?: string
}

export interface Country {
    id: number
    code: string
    name: string
    active?: boolean
    sort_order?: number
}

export interface Fertilizer {
    id: number
    name: string
    type: string
    fertilizer_key: string
    fertilizer_label?: string
    country: string
    weight: number
    use_case: string
    available: boolean
    cis: boolean
    cim: boolean
    sort_order?: number
}

export interface FertilizerPrice {
    id: number
    country: string
    fertilizer_key: string
    min_price: number
    max_price: number
    price_per_bag: number
    price_active: boolean
    sort_order?: number
    desc?: string
}

export interface MaizePrice {
    id: number
    country: string
    produce_type: string
    min_price: number
    max_price: number
    min_local_price: number
    max_local_price: number
    min_usd: number
    max_usd: number
    price_active: boolean
    sort_order?: number
}

export interface CassavaPrice {
    id: number
    country: string
    min_price: number
    max_price: number
    min_local_price: number
    max_local_price: number
    min_usd: number
    max_usd: number
    price_active: boolean
    sort_order?: number
}

export interface PotatoPrice {
    id: number
    country: string
    min_price: number
    max_price: number
    min_local_price: number
    max_local_price: number
    min_usd: number
    max_usd: number
    price_active: boolean
    sort_order?: number
}

export interface StarchFactory {
    id: number
    factory_name: string
    factory_label: string
    country: string
    factory_active: boolean
    sort_order?: number
}

export interface StarchPrice {
    id: number
    starch_factory_id: number
    price_class: number
    min_starch: number
    range_starch?: string
    price: number
    currency?: string
}

export interface InvestmentAmount {
    id: number
    country: string
    investment_amount: number
    area_unit: string
    price_active: boolean
    sort_order?: number
}

export interface OperationCost {
    id: number
    operation_name: string
    operation_type: string
    country_code: string
    min_cost: number
    max_cost: number
    is_active: boolean
}

export interface Currency {
    id: number
    country_code: string
    country: string
    currency_name: string
    currency_code: string
    currency_symbol: string
    currency_native_symbol?: string
    name_plural?: string
}

export interface CassavaUnit {
    id: number
    label: string
    unit_weight: number
    description?: string
    is_active: boolean
    sort_order?: number
}

export interface Translation {
    id: number
    key: string
    en: string
    sw?: string
    rw?: string
}

export interface DefaultPrice {
    id: number
    country: string
    item: string
    price: number
    unit: string
    currency?: string
}

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
    countries: Pick<Country, 'id' | 'code' | 'name'>[]
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

export interface ApiKey {
    id: number
    name: string
    key_prefix: string
    is_active: boolean
    abilities: string[] | null
    last_used_at: string | null
    expires_at: string | null
    created_at: string
    user: Pick<User, 'id' | 'name' | 'email'> | null
}
