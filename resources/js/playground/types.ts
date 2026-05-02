export type Scenario = 'FR' | 'IC' | 'PP' | 'SPHS' | 'COMPLETE'
export type IntercropCrop = 'MAIZE' | 'POTATO'
export type Step = 1 | 2 | 3 | 4 | 5

export interface Country {
    id: number
    code: string
    name: string
}

export interface Fertilizer {
    id: number
    name: string
    fertilizer_key: string
    fertilizer_type?: string
    weight: number
    country: string
    available: boolean
}

export interface FertilizerEntry {
    key: string
    name: string
    fertilizer_type: string
    weight: number
    price: number
    selected: boolean
}

export interface FormState {
    scenario: Scenario
    intercropCrop: IntercropCrop
    country: string
    fieldSize: string
    areaUnit: string
    mapLat: string
    mapLong: string
    lang: string
    fieldYield: string
    soilQuality: string
    riskAttitude: string
    cassavaProduceType: string
    maizeProduceType: string
    sweetPotatoProduceType: string
    plantingDate: string
    harvestDate: string
    plantingDateWindow: string
    harvestDateWindow: string
}
