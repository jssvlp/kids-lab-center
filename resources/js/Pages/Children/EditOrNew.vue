<template>
  <jet-dialog-modal :show="toggleNewOrEditChildrenModal" @close="toggleModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <div class="flex mt-4">
                    <jet-input type="text" class="mt-1 block w-full" placeholder="Nombre completo"
                                ref="name"
                                v-model="form.name"
                        />

                    
                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
                <div class="flex mt-4">
                    <VueTailwindPicker
                        class="mt-1  block w-full"
                                @change="(v) => checkin = v"
                            >
                            <jet-input type="text" v-model="form.birth_date"/>
                            
                    </VueTailwindPicker>
                    <jet-input-error :message="form.error" class="mt-2" />
                    <select class="w-full border bg-white rounded  py-2 outline-none" v-model="form.gender"> 
                        <option selected disabled>¿Niño o niña?</option>
                        <option class="py-1" value="Niño">Niño</option>
                        <option class="py-1" value="Niña">Niña</option>
                    </select>
                </div>
                <div class="flex mt-4">
                    <jet-input type="text" class="mt-1 block w-3/5" placeholder="Nombre completo"
                                ref="name"
                                v-model="form.parent.name"
                                disabled
                    />
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>s
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
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import {mapState,mapActions} from 'vuex'
import VueTailwindPicker from 'vue-tailwind-picker'
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
        VueTailwindPicker
    },
    data: ()=>({
        form: {
            id:'',
            name:'',
            birth_date: '',
            gender:'¿Niño o niña?',
            parent:{}

        },
    }),
    mounted(){
       console.log(this.data)
       if(this.data){
            this.form.id = this.data.id
            this.form.name = this.data.name
            this.form.birth_date = this.data.birth_date,
            this.form.parent = this.data.parent
       }
    },
    computed:{
        ...mapState(['editingOrCreatingChildren'])
    },
    methods:{
        setBirthDayDate(date){
            this.form.birth_date = date
        },
        ...mapActions({
            toggleNewOrEditChildrenModal: "toggleNewOrEditChildrenModal"
        }),
        toggleModal(){
            this.toggleNewOrEditChildrenModal()
        },
        onSave(){
            if(this.form.id){
                this.$inertia.patch(`/children/${this.form.id}`, this.form)
                .then( (data) =>{
                    this.toggleNewOrEditChildrenModal()
                })
            }
            else{
                this.$inertia.post('/children', this.form)
                .then( (data) =>{
                    this.toggleNewOrEditChildrenModal()
                })
            }
        },
    }
}
</script>

<style>

</style>