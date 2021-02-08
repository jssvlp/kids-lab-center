<template>
  <jet-dialog-modal :show="editingOrCreatingParent" @close="toggleModal">
            <template #title>
                {{ title }}
            </template>
            <template #content>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'Nombre completo'"></jet-label>
                        <jet-input type="text" class="mt-1 w-full capitalize" placeholder="Escriba un nombre"
                                    ref="name"
                                    :class="{ 'border-red-500': $v.form.name.$error }"
                                    v-model="form.name"
                        />
                        <jet-input-error v-if="!$v.form.name.required && $v.form.name.$error" :message="'El nombre es requerido'" class="mt-2" />
                        <jet-input-error v-if="!$v.form.name.minLength && $v.form.name.$error" :message="'Nombre muy corto'" class="mt-2" />    
                    </div>
                    <div class="w-full">
                        <jet-label :value="'Teléfono'"></jet-label>
                        <jet-input type="text" class="mt-1 ml-3 w-full" placeholder="Escriba un teléfono"
                                ref="name"
                                :class="{ 'border-red-500': $v.form.phone.$error }"
                                v-model="form.phone"
                                v-mask="'(###) ###-####'"
                        />
                        <jet-input-error v-if="!$v.form.phone.required && $v.form.phone.$error" :message="'El télefono es requerido'" class="mt-2" />
                    </div>
                    
                </div>
                <div class="flex mt-4">
                    <div class="w-full">
                        <jet-label :value="'Dirección'"></jet-label>
                        <jet-input type="text" class="mt-1 block w-full capitalize" placeholder="Dirección"
                                ref="name"
                                :class="{ 'border-red-500': $v.form.address.$error }"
                                v-model="form.address"
                        />
                        <jet-input-error v-if="!$v.form.address.required && $v.form.address.$error" :message="'La dirección es requerida'" class="mt-2" />
                        <jet-input-error v-if="!$v.form.address.minLength && $v.form.address.$error" :message="'Mínimo 10 caracteres'" class="mt-2" />    
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="w-full" >
                        <jet-label :value="'¿Padre o Madre?'"></jet-label>
                        <select class="border bg-white rounded px-3 py-2 outline-none w-full" v-model="form.kinship"
                                    :class="{ 'border-red-500': $v.form.kinship.$error }"
                                > 
                            <option class="py-1" value="Padre">Padre</option>
                            <option class="py-1" value="Madre">Madre</option>
                        </select>
                        <jet-input-error v-if="!$v.form.kinship.minLength && $v.form.kinship.$error" :message="'Debe seleccionar un valor'" class="mt-2" />
                    </div>
                </div>
            </template>

            <template #footer>
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
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetLabel from '@/Jetstream/Label'
import {mapState,mapActions} from 'vuex'
import { required, minLength, bealtween } from 'vuelidate/lib/validators'
import NProgress from 'nprogress'
const  capitalize = require('capitalize')
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
    validations:{
        form: {
            name: {
                required,
                minLength: minLength(6)
            },
            address:{
                required,
                minLength: minLength(10)
            },
            phone:{
                required
            },
            kinship:{
                required
            }
        }
    },
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
            this.$v.form.$touch();
            if(this.$v.form.$error) return
            NProgress.start();
            if(this.form.id != ''){
                this.$inertia.patch(`/parents/${this.form.id}`, this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditParentModal()
                    NProgress.done();
                })
            }
            else{
                this.$inertia.post('/parents', this.form)
                .then( (data) =>{
                    this.$emit('refresh')
                    this.toggleNewOrEditParentModal()
                    NProgress.done();
                    console.log(this.successMessage)
                })
            }
        },
    }
}
</script>

<style>

</style>