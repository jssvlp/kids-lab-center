<template>
  <jet-dialog-modal :show="toggleNewOrEditInsuranceModal" @close="toggleModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <div class="flex w-full mt-4">
                    <div class="w-full">
                        <jet-label :value="'Nombre completo'"></jet-label>
                        <jet-input type="text" class="mt-1 w-full" placeholder="Nombre"
                                ref="name"
                                v-model="form.name"
                                :class="{ 'border-red-500': $v.form.name.$error }"
                        />
                        <jet-input-error v-if="!$v.form.name.required && $v.form.name.$error" :message="'Es obligatorio escribir un nombre para la aseguradora'" class="mt-2" />
                        <jet-input-error v-if="!$v.form.name.minLength && $v.form.name.$error" :message="'Mínimo 6 caracteres'" class="mt-2" />   
                    </div>
                    <div class="w-full">
                        <jet-label :value="'Teléfono'"></jet-label>
                        <jet-input type="text" class="mt-1 ml-3 w-full" placeholder="Teléfono"
                            ref="name"
                            v-mask="'(###) ###-####'"
                            v-model="form.phone"
                    />
                    </div>
                    
                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
            </template>

            <template #footer>
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
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetLabel from '@/Jetstream/Label'
import {mapState,mapActions} from 'vuex'
import { required, minLength, between } from 'vuelidate/lib/validators'

export default {
    name:'EditOrNewChildren',
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
        JetLabel
    },
    data: ()=>({
        form: {
            id:'',
            name:'',
            phone: '',
        },
    }),
    validations:{
        form: {
            name:{
                required,
                minLength: minLength(6)
            }
        }

    },
    mounted(){
       if(this.data){
            this.form.id = this.data.id
            this.form.name = this.data.name
            this.form.phone = this.data.phone
       }
    },
    computed:{
        ...mapState(['editingOrCreatingInsurance'])
    },
    methods:{
        ...mapActions({
            toggleNewOrEditInsuranceModal: "toggleNewOrEditInsuranceModal"
        }),
        toggleModal(){
            this.toggleNewOrEditInsuranceModal()
        },
        onSave(){
            this.$v.form.$touch();
            if(this.$v.form.$error) return
            if(this.form.id){
                this.$inertia.patch(`/insurances/${this.form.id}`, this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditInsuranceModal()
                })
            }
            else{
                this.$inertia.post('/insurances', this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditInsuranceModal()
                })
            }
        },
    }
}
</script>

<style>

</style>