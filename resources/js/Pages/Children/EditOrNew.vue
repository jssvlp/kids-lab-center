<template>
  <jet-dialog-modal :show="editingOrCreatingChild" @close="toggleModal">
            <template #title>
                <div class="ml-10 mt-5">{{title}}</div>
            </template>

            <template #content>
                <div class="flex mt-4">
                    <div class="w-full mx-10">
                        <jet-label :value="'Nombre completo'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full" placeholder="Nombre completo"
                                    ref="name"
                                    v-model="form.name"
                            />
                        <jet-input-error :message="form.error" class="mt-2" />
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="w-full mx-10">
                        <PlanSelect :selectedForEdition="plan"/>
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="w-full mx-10">
                        <jet-label :value="'Fecha de nacimiento'"></jet-label>
                        <datetime
                            type="date"
                            v-model="form.birth_date"
                            input-class="border w-full bg-white rounded  py-2 outline-none"
                        ></datetime>
                    </div>
                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
                <div class="flex mt-4 mx-10">
                    <div class="w-full">
                        <jet-label :value="'¿Niño o Niña?'"></jet-label>
                        <select class="border w-full bg-white rounded  py-2 outline-none" v-model="form.gender"> 
                            <option class="py-1" value="Niño">Niño</option>
                            <option class="py-1" value="Niña">Niña</option>
                        </select>
                    </div>
                </div>
                <div class="flex mt-4 mx-10">
                    <div class="w-full">
                        <jet-label :value="'Padre/Madre'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full" placeholder="Nombre completo"
                                    ref="name"
                                    v-model="form.parent.name"
                                    disabled
                        />
                    </div>
                </div>
                <br>
                
            </template>

            <template #footer class="">
                <jet-secondary-button @click.native="toggleModal">
                    Cancelar
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="onSave" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
        PlanSelect
    },
    data: ()=>({
        form: {
            id: null,
            name: '',
            birth_date: null,
            gender: '',
            parent: {},
        },
        plan: null
    }),
    created(){
        if(this.data.id){
            this.form.birth_date = this.data.birth_date
        }
    },

    mounted(){
       if(this.data.id){
            this.form.id = this.data.id
            this.form.name = this.data.name
            this.plan = this.data.plan
            this.form.gender = this.data.gender
            this.setPlanForEditOrNewChild(this.data.plan)
       }
       this.form.parent = this.data.dad_or_mom
    },
    computed:{
        ...mapState(['editingOrCreatingChild','planForEditOrNewChild'])
    },
    methods:{
        setBirthDayDate(date){
            this.form.birth_date = date
        },
        ...mapActions({
            toggleNewOrEditChildModal: "toggleNewOrEditChildModal",
            setPlanForEditOrNewChild: "setPlanForEditOrNewChild"
        }),
        toggleModal(){
            this.toggleNewOrEditChildModal()
        },
        onSave(){
            const child = {
                    'name' : this.form.name,
                    'birth_date' : this.form.birth_date,
                    'gender': this.form.gender,
                    'dad_or_mom_id': this.form.parent.id,
                    'plan_id' : this.planForEditOrNewChild.id
                }


            if(this.form.id != null){
                this.$inertia.patch(`/children/${this.form.id}`, child)
                .then( (data) =>{
                    this.toggleNewOrEditChildModal()
                })
            }
            else{
                this.$inertia.post('/children', child)
                .then( (data) =>{
                    this.toggleNewOrEditChildModal()
                    console.log(data)
                })
            }
        },
    }
}
</script>

<style>

</style>