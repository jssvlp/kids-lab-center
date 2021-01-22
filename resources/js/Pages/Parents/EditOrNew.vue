<template>
  <jet-dialog-modal :show="editingOrCreatingParent" @close="toggleModal">
            <template #title>
                {{ title }}
            </template>
            <template #content>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'Nombre completo'"></jet-label>
                        <jet-input type="text" class="mt-1 w-full" placeholder="Escriba un nombre"
                                    ref="name"
                                    v-model="form.name"
                        />  
                    </div>
                    <div class="w-full">
                        <jet-label :value="'Teléfono'"></jet-label>
                        <jet-input type="text" class="mt-1 ml-3 w-full" placeholder="Escriba un teléfono"
                                ref="name"
                                v-model="form.phone"
                        />
                    </div>
                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'Dirección'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full" placeholder="Dirección"
                                ref="name"
                        v-model="form.address"/>
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'¿Padre o Madre?'"></jet-label>
                    <select class="border bg-white rounded px-3 py-2 outline-none w-full" v-model="form.kinship"> 
                        <option class="py-1" value="Padre">Padre</option>
                        <option class="py-1" value="Madre">Madre</option>
                    </select>
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
import { Inertia } from '@inertiajs/inertia'
export default {
    props: {
        title: {
            default: 'Nuevo padre/madre',
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
            address:'',
            phone: '',
            kinship:'',
            error:''
        },
    }),
    mounted(){
      
       if(this.data){
            this.form.id = this.data.id
            this.form.name = this.data.name
            this.form.address = this.data.address
            this.form.phone = this.data.phone
            this.form.kinship = this.data.kinship
       }
    },
    computed:{
        ...mapState(['editingOrCreatingParent'])
    },
    methods:{
        ...mapActions({
            toggleNewOrEditParentModal: "toggleNewOrEditParentModal"
        }),
        toggleModal(){
            this.toggleNewOrEditParentModal()
        },
        onSave(){
            if(this.form.id != ''){
                this.$inertia.patch(`/parents/${this.form.id}`, this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditParentModal()
                })
            }
            else{
                this.$inertia.post('/parents', this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditParentModal()
                    
                })
            }
        },
    }
}
</script>

<style>

</style>