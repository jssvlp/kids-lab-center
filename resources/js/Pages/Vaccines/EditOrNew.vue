<template>
  <jet-dialog-modal :show="editingOrCreatingVaccine" @close="toggleModal">
            <template #title>
                <div class="ml-10 mt-5">{{title}}</div>
            </template>
            <template #content>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'Nombre vacuna'"></jet-label>
                        <jet-input type="text" class="mt-1 w-full" placeholder="Escriba un nombre"
                                    ref="name"
                                    v-model="form.name"
                        />  
                    </div>
                    <div class="w-full">
                        <jet-label :value="'Precio'"></jet-label>
                        <jet-input type="number" class="mt-1 ml-3 w-full" placeholder="Digite un precio"
                                ref="name"
                                v-model="form.price"
                        />
                    </div>
                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
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

export default {
    props: {
        title: {
            default: 'Nueva vacuna',
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
 
    },
    data: ()=>({
        form: {
            id: null,
            name: '',
            price: ''
        },

    }),
    mounted(){
       if(this.data != null){
             this.form.price = this.data.price
            this.form.name = this.data.name
       }
      
    },
    computed:{
        ...mapState(['editingOrCreatingVaccine'])
    },
    methods:{
        ...mapActions({
            toggleNewOrEditVaccineModal: "toggleNewOrEditVaccineModal",

        }),
        toggleModal(){
            this.toggleNewOrEditVaccineModal()
        },
        onSave(){
            const vaccine = {
                    'name' : this.form.name,
                    'price' : this.form.price,
                }

            if(this.form.id != null){
                this.$inertia.patch(`/vaccines/${this.form.id}`, vaccine)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditVaccineModal()
                })
            }
            else{
                this.$inertia.post('/vaccines', vaccine)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditVaccineModal()
                })
            }
        },
    }
}
</script>

<style>

</style>