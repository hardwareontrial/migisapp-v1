import { Ability } from '@casl/ability'
import { initialAbility } from './config'
import store from '@/store'

const data = JSON.parse(localStorage.getItem('userdata'))
const existingAbility = data ? data.ability : null

export default new Ability(existingAbility || initialAbility)
