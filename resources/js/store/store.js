import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    editingOrCreatingParent: false,
    editingOrCreatingInsurance: false,
    editingOrCreatingChildren: false,
  },
  mutations: {
    TOGGLE_NEW_OR_EDIT_PARENT_MODAL(state){
      state.editingOrCreatingParent = !state.editingOrCreatingParent
    },
    TOGGLE_NEW_OR_EDIT_INSURANCE_MODAL(state){
      state.editingOrCreatingInsurance = !state.editingOrCreatingInsurance
    },
    TOGGLE_NEW_OR_EDIT_CHILDREN_MODAL(state){
      state.editingOrCreatingChildren = !state.editingOrCreatingChildren
    }
  },
  actions: {
    toggleNewOrEditParentModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_PARENT_MODAL')
    },
    toggleNewOrEditInsuranceModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_INSURANCE_MODAL')
    },
    toggleNewOrEditChildrenModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_CHILDREN_MODAL')
    },
  }
})