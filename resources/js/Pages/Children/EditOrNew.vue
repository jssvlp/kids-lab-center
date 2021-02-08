<template>
  <jet-dialog-modal :show="editingOrCreatingChild" @close="toggleModal">
            <template #title>
                <div class="ml-10 mt-5">{{title}}</div>
            </template>

            <template #content>
                <div class="flex mt-4">
                    <div class="w-full mx-10">
                        <jet-label :value="'Nombre completo'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full capitalize" placeholder="Nombre completo"
                                    ref="name"
                                    :class="{ 'border-red-500': $v.form.name.$error }"
                                    v-model="form.name"
                            />
                        <jet-input-error v-if="!$v.form.name.required && $v.form.name.$error" :message="'El nombre es requerido'" class="mt-2" />
                        <jet-input-error v-if="!$v.form.name.minLength && $v.form.name.$error" :message="'Nombre muy corto'" class="mt-2" />
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="w-full ml-10">
                        <PlanSelect :selectedForEdition="plan" @selected="verifyPlanNumber" @onFocus="onFocus"  :showList="showList" v-click-outside="closePlanList" />
                    </div>
                    <div class="w-full mx-10">
                        <jet-label :value="'Id/Número seguro:'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full " placeholder="1234"
                                    ref="name"
                                    @blur.native="verifyPlanNumber"
                                    v-model="form.health_insurance_id"
                            />
                        <jet-input-error v-if="insuranceIdExists" :message="'Ya existe un paciente con este id.'" class="mt-2" />
                        <jet-input-error v-if="insuranceIdRequired" :message="'Este campo es obligatorio si elige un plan.'" class="mt-2" />

                    </div>
                </div>
                
                <div class="flex mt-4">
                    <div class="w-full mx-10">
                        <jet-label :value="'Fecha de nacimiento'"></jet-label>
                        <datetime
                            type="date"
                            v-model="form.birth_date"
                            zone="America/Santo_Domingo"
                            value-zone="America/Santo_Domingo"
                            :class="{ 'border-red-500': $v.form.birth_date.$error }"
                            input-class="border w-full bg-white rounded  py-2 px-2 outline-none"
                        ></datetime>
                        <jet-input-error v-if="!$v.form.name.required && $v.form.birth_date.$error" :message="'La fecha de nacimiento es requerida'" class="mt-2" />
                    </div>
                </div>
                <div class="flex mt-4 mx-10">
                    <div class="w-full">
                        <jet-label :value="'¿Niño o Niña?'"></jet-label>
                        <select class="border w-full bg-white rounded  py-2 px-2 outline-none" v-model="form.gender" :class="{ 'border-red-500': $v.form.gender.$error }"> 
                            <option class="py-1" value="Niño">Niño</option>
                            <option class="py-1" value="Niña">Niña</option>
                        </select>
                        <jet-input-error v-if="!$v.form.name.required && $v.form.gender.$error" :message="'Debe elegir el sexo'" class="mt-2" />
                    </div>
                </div>
                <div class="flex mt-4 mx-10">
                    <div class="w-full">
                        <jet-label :value="'Padre/Madre'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full" placeholder="Nombre completo"
                                    ref="name"
                                    v-model="form.parent.name"
                                    
                        />
                    </div>
                </div>
                <br>
                
            </template>

            <template #footer class="">
                <jet-secondary-button @click.native="toggleModal">
                    Cancelar
                </jet-secondary-button>

                <jet-button class="ml-2 bg-trendy-pink-400" @click.native="onSave" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Guardar
                </jet-button>
            </template>
        </jet-dialog-modal>
</template>

<script>
import JetDialogModal from '@/Jetstream/DialogModal'
import JetButton from '@/Jetstream/Button'
import JetInputError from '@/Jetstream/InputError'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import {mapState,mapActions} from 'vuex'
import VueTailwindPicker from 'vue-tailwind-picker'
import { Datetime } from 'vue-datetime'
import PlanSelect from '../Insurances/PlanSelect'
import { required, minLength, between } from 'vuelidate/lib/validators'
import NProgress from 'nprogress'
import Datepicker from 'vuejs-datepicker';
import moment from 'moment'
export default {
    props: {
        title: {
            default: 'Nueva aseguradora',
        },
        data:{
            default: null
        }
    },
    components:{
        JetDialogModal,
        JetInputError,
        JetSecondaryButton,
        JetInput,
        JetButton,
        JetLabel,
        VueTailwindPicker,
        Datetime,
        PlanSelect,
        Datepicker
    },
    data: ()=>({
        form: {
            id: null,
            name: '',
            birth_date: null,
            health_insurance_id:'',
            gender: '',
            parent: {},
           
        },
        plan: null,
        allowWritePlan: false,
        insuranceIdExists: false,
        showList: true
    }),
    validations:{
        form:{
            name: {
                required,
                minLength: minLength(6)
            },
            birth_date: {
                required
            },
            gender:{
                required
            }
        }
    },
    created(){
        if(this.data){
            this.form.birth_date = this.data.birth_date
        }
    },

    mounted(){
       if(this.data){
            this.form.id = this.data.id
            this.form.name = this.data.name
            this.plan = this.data.plan
            this.form.gender = this.data.gender
            this.form.health_insurance_id = this.data.health_insurance_id
            this.setPlanForEditOrNewChild(this.data.plan)
            this.form.parent = this.data.dad_or_mom
       }
       
    },
    computed:{
        ...mapState(['editingOrCreatingChild','planForEditOrNewChild']),
        insuranceIdRequired(){
             if(this.planForEditOrNewChild && this.form.health_insurance_id == ''){
                 return true
             }
             else{
                 return false
             }
         }
    },
    methods:{
        setBirthDayDate(date){
            this.form.birth_date =  moment(this.form.birth_date).format('YYYY-MM-DD');
        },
        ...mapActions({
            toggleNewOrEditChildModal: "toggleNewOrEditChildModal",
            setPlanForEditOrNewChild: "setPlanForEditOrNewChild",
            setParentForNewChild: 'setParentForNewChild'
        }),
        toggleModal(){
            this.toggleNewOrEditChildModal()
        },
        onSave(){
            this.$v.form.$touch();
            if(this.$v.form.$error) return
            const child = {
                    'name' : this.form.name,
                    'birth_date' : this.form.birth_date,
                    'gender': this.form.gender,
                    'dad_or_mom_id': this.form.parent.id,
                    'plan_id' : this.planForEditOrNewChild != null ? this.planForEditOrNewChild.id : null,
                    'health_insurance_id' : this.form.health_insurance_id
                }

            this.setPlanForEditOrNewChild(null);
            this.setParentForNewChild(null);

            NProgress.start()
            if(this.form.id != null){
                this.$inertia.patch(`/children/${this.form.id}`, child)
                .then( (data) =>{
                    NProgress.done()
                    this.toggleNewOrEditChildModal()
                    this.$emit('refresh')
                })
            }
            else{
                this.$inertia.post('/children', child)
                .then( (data) =>{
                    NProgress.done()
                    this.toggleNewOrEditChildModal()
                    this.$emit('refresh')
                })
            }
        },
        closePlanList(){
            this.showList = false
        },
        onFocus(){
            this.showList = true
        },
        verifyPlanNumber(){

            if( this.planForEditOrNewChild && this.form.health_insurance_id){
                const data = {
                    plan_id: this.planForEditOrNewChild.id,
                    health_insurance_id: this.form.health_insurance_id
                }

                axios.post('/children/verifyplannumber',data).
                then(data =>{
                    this.insuranceIdExists = data.data.exists
                    
                });
            }
            
        }
    }
}
</script>

<style>

</style>