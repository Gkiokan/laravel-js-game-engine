import { make } from 'vuex-pathify'

export const state = {
    player: null,
    enemies: [],
    level: 'BaseLevel',
    
    acceleration: 10,
    dash: 50,
    coords: {
        x: 0,
        y: 0,
        left: 0,
        top: 0,
        bottom: 0,
        right: 0,
    },
    keys: {
        ArrowLeft: false,
        ArrowUp: false,
        ArrowRight: false,
        ArrowDown: false,
        KeyA: false,
        KeyS: false,
        KeyD: false,
        KeyF: false,        
        Space: false,
    },
    window: {
        width: 0,
        height: 0,
        offsetX: 200,
        offsetY: 100,
    }
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
