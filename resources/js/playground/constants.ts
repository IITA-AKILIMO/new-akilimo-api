import type {IntercropCrop, Scenario} from './types'

export const SCENARIOS: {id: Scenario; icon: string; title: string; desc: string}[] = [
    {id: 'FR',       icon: '🌱',  title: 'Fertilizer Recommendations', desc: 'Which fertilizers to apply and in what quantities'},
    {id: 'IC',       icon: '🌽',  title: 'Intercropping',              desc: 'Should I grow cassava alongside maize or potato?'},
    {id: 'PP',       icon: '🌿',  title: 'Planting Practices',         desc: 'Land preparation methods and field operations'},
    {id: 'SPHS',     icon: '📅',  title: 'Planting Schedule',          desc: 'Optimal planting and harvest timing'},
    {id: 'COMPLETE', icon: '🗺️', title: 'Complete Farm Plan',         desc: 'All recommendations in one response'},
]

export const AREA_UNITS = [
    {value: 'ha',   label: 'Hectare (ha)'},
    {value: 'acre', label: 'Acre'},
    {value: 'm2',   label: 'Square metre (m²)'},
    {value: 'are',  label: 'Are'},
]

export const LANGUAGES = [
    {value: 'en', label: 'English'},
    {value: 'sw', label: 'Swahili'},
    {value: 'fr', label: 'French'},
]

export const CASSAVA_PRODUCE_TYPES = [
    {value: 'roots', label: 'Fresh Roots'},
    {value: 'chips', label: 'Dried Chips'},
    {value: 'flour', label: 'Cassava Flour'},
    {value: 'gari',  label: 'Gari'},
]

export const MAIZE_PRODUCE_TYPES = [
    {value: 'fresh_cob', label: 'Fresh Cob'},
    {value: 'grain',     label: 'Dry Grain'},
]

export const SWEET_POTATO_PRODUCE_TYPES = [
    {value: 'tubers', label: 'Fresh Tubers'},
    {value: 'flour',  label: 'Flour'},
]

export const COUNTRY_COORDS: Record<string, [number, number]> = {
    NG: [7.3451,  6.9660],
    TZ: [-5.6408, 35.3456],
    GH: [7.4923,  -1.2756],
    RW: [-1.9997, 29.9486],
    BI: [-3.3335, 29.9238],
}

export const COUNTRY_INTERCROP: Partial<Record<string, IntercropCrop>> = {
    NG: 'MAIZE',
    TZ: 'POTATO',
}

export const INITIAL_FORM_STATE = {
    scenario:              'FR'        as Scenario,
    intercropCrop:         'MAIZE'     as IntercropCrop,
    country:               '',
    fieldSize:             '1',
    areaUnit:              'ha',
    mapLat:                '',
    mapLong:               '',
    lang:                  'en',
    fieldYield:            '10',
    soilQuality:           '3',
    riskAttitude:          '0',
    cassavaProduceType:    'roots',
    maizeProduceType:      'fresh_cob',
    sweetPotatoProduceType:'tubers',
    plantingDate:          '',
    harvestDate:           '',
    plantingDateWindow:    '0',
    harvestDateWindow:     '0',
}
