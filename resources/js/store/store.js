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
    planForEditOrNewChild: null,
    childForNewVisit: null,
    titleForVisit:'Nueva visita',
    vaccineList: []
  },
  mutations: {
    PUSH_TO_VACCINE_LIST(state, vaccine){
      state.vaccineList.push(vaccine)
    },
    REMOVE_FROM_VACCINE_LIST(state,vaccine){
      //TODO: remove from vaccine list
    },

    SET_TITLE_FOR_VISIT(state,title){
      state.titleForVisit = title
    },
    SET_CHILD_FOR_NEW_VISIT(state,child){
      state.childForNewVisit = child
    },
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
    },
    setChildForNewVisit({commit},child){
      commit('SET_CHILD_FOR_NEW_VISIT',child)
    },
    setTitleForVisit({commit},title){
      commit('SET_TITLE_FOR_VISIT',title)
    },
    pushToVaccineList({commit},vaccine){
      commit('PUSH_TO_VACCINE_LIST',vaccine)
    },
    removeFromVaccineList({commit},vaccine){
      commit('REMOVE_FROM_VACCINE_LIST',vaccine)
    }
  }
})