<template>
  <jet-dialog-modal :show="toggleNewOrEditInsuranceModal" @close="toggleModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <div class="flex mt-4">
                    <jet-input type="text" class="mt-1 block w-3/5" placeholder="Nombre completo"
                                ref="name"
                                v-model="form.name"
                        />
                    <jet-input type="text" class="mt-1 ml-3 block w-2/5" placeholder="Teléfono"
                            ref="name"
                            v-model="form.phone"
                    />
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

import {mapState,mapActions} from 'vuex'
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
        JetButton
    },
    data: ()=>({
        form: {
            id:'',
            name:'',
            phone: '',
        },
    }),
    mounted(){
       console.log(this.data)
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
            if(this.form.id){
                this.$inertia.patch(`/insurances/${this.form.id}`, this.form)
                .then( (data) =>{
                    this.toggleNewOrEditInsuranceModal()
                })
            }
            else{
                this.$inertia.post('/insurances', this.form)
                .then( (data) =>{
                    this.toggleNewOrEditInsuranceModal()
                })
            }
        },
    }
}
</script>

<style>

</style>