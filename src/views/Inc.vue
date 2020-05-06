<template>
    <v-container>
        <v-row>
            <v-card
                width='55%'
                raised
                class='mx-auto'
                color='grey lighten-4'            
                >
                <v-card-text class="pa-0">
                        <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                        <p class="subtitle-1 font-weight-medium mx-8">{{ sub_title }}</p>
                </v-card-text>
                <hr/>  
                <v-row>                    
                    <v-col cols='11' class="mx-auto">
                        <label>Краткое описание:</label>
                        <v-text-field 
                            v-model='title_inc' 
                            outlined 
                            solo 
                            dense
                            :error-messages='title_inc_err' 
                            :label='title_inc_label'
                        ></v-text-field>
                        <v-switch v-model="switch_1c" @change="switch_mon = false" label='Проблема связана с 1С' inset class="mt-n2"></v-switch>
                        <v-switch v-model="switch_mon" @change="switch_1c = false" label='Проблема связана с мониторингом' inset class="mt-n2"></v-switch>                        
                        <v-expand-transition>
                            <div v-if="switch_1c">                            
                                <label>Выбор направления 1С:</label>
                                <v-select
                                :items='type_1c'
                                label='Выберите необходимое направление 1C'
                                outlined
                                solo
                                dense
                                ></v-select>                                                       
                            </div> 
                        </v-expand-transition>                       
                        <label>Подробное описание:</label>
                        <v-textarea 
                            v-model="cmnt" 
                            outlined solo 
                            rows='7'
                            label="Подробное описание помагает быстрее решить проблему"
                        ></v-textarea>
                        <v-file-input 
                            type="file" 
                            v-model="file" 
                            label="Выберите файл или файлы" 
                            chips outlined solo multiple dense 
                            ref="file" 
                            prepend-icon='' 
                            prepend-inner-icon='mdi-file'
                        ></v-file-input> 
                    </v-col>
                </v-row>
                <hr/>                
                <v-card-actions class='py-4'>
                    <div class='mx-auto'>
                        <v-btn class='mx-1' color='blue lighten-1 white--text' @click='formSend()'>
                            Отправить
                        </v-btn>
                        <v-btn class='mx-1' @click='formCancl()'>
                            Отмена
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-row>    
    </v-container>
</template>

<script>
export default {
    data:() => ({
        title: 'Новое обращение',
        sub_title: 'Если у вас возникли проблемы с используемым оборудованием, программным обеспечением или вы нуждаетесь в консультации специалистов, заполните предлагаемую форму и нажмите отправить.',
        title_inc: '',
        title_inc_err: '',
        title_inc_label: 'Буквально в двух словах',
        type_1c: ['1','2','2','2','2','2','2','2','2','2'],
        switch_1c: '',
        switch_mon: '',
        cmnt: '',
        file: null
    }),
    methods: {
        swicth1: function(){
            this.switch_mon = !this.switch_mon; 
        }
    }
}
</script>