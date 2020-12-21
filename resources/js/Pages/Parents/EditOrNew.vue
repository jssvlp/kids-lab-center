<template>
  <jet-dialog-modal :show="editingOrCreatingParent" @close="toggleModal">
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
                <div class="flex mt-4">
                    <jet-input type="text" class="mt-1 block w-full" placeholder="Dirección"
                                ref="name"
                                v-model="form.address"/>
                </div>
                <div class="flex mt-4">
                    <select class="w-3/5 border bg-white rounded px-3 py-2 outline-none" v-model="form.kinship"> 
                        <option class="py-1" value="Padre">Padre</option>
                        <option class="py-1" value="Madre">Madre</option>
                    </select>
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
        JetButton
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
                    this.toggleNewOrEditParentModal()
                })
            }
            else{
                this.$inertia.post('/parents', this.form)
                .then( (data) =>{
                    this.toggleNewOrEditParentModal()
                })
            }
        },
    }
}
</script>

<style>

</style>