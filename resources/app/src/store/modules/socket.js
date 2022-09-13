import { make } from 'vuex-pathify'

export const state = {
    live: [
        { empty: true },
        { empty: true },
        { empty: true },
        { empty: true },
        { empty: true },
        { empty: true },        
    ],
}


// make all mutations
export const mutations = {
    ...make.mutations(state),

}

// actions
export const actions = {
    ...make.actions(state),
}

// getters
export const getters = {
  // make all getters (optional)
  ...make.getters(state),
}

// console.log({
//     mutations, actions, getters
// })
