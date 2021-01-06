import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    editingOrCreatingParent: false,
    editingOrCreatingInsurance: false,
    editingOrCreatingChild: false,
    editingOrCreatingVaccine: false,
    parentForNewChild: null,
    planForEditOrNewChild: null
  },
  mutations: {
    SET_PARENT_NEW_CHILD(state, parent){
      state.parentForNewChild = parent;
    },
    SET_PLAN_NEW_CHILD(state, plan){
      state.planForEditOrNewChild = plan;
    },
    TOGGLE_NEW_OR_EDIT_PARENT_MODAL(state){
      state.editingOrCreatingParent = !state.editingOrCreatingParent
    },
    TOGGLE_NEW_OR_EDIT_INSURANCE_MODAL(state){
      state.editingOrCreatingInsurance = !state.editingOrCreatingInsurance
    },
    TOGGLE_NEW_OR_EDIT_CHILD_MODAL(state){
      state.editingOrCreatingChild = !state.editingOrCreatingChild
    },
    TOGGLE_NEW_OR_EDIT_VACCINE_MODAL(state){
      state.editingOrCreatingVaccine = !state.editingOrCreatingVaccine
    }
  },
  actions: {
    toggleNewOrEditVaccineModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_VACCINE_MODAL')
    },
    toggleNewOrEditParentModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_PARENT_MODAL')
    },
    toggleNewOrEditInsuranceModal({commit}){
      commit('TOGGLE_NEW_OR_EDIT_INSURANCE_MODAL')
    },
    toggleNewOrEditChildModal({commit}){
      
      commit('TOGGLE_NEW_OR_EDIT_CHILD_MODAL')
    },
    setParentForNewChild({commit},parent){
      commit('SET_PARENT_NEW_CHILD',parent)
    },
    setPlanForEditOrNewChild({commit},plan){
      commit('SET_PLAN_NEW_CHILD',plan)
    }
  }
})