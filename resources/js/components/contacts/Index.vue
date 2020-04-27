<template>
    <div class="contacts mb-5">
        <ul v-if="contacts" class="card">
            <li v-for="({ id, first_name, last_name, phons }, index) in contacts" v-bind:key="index">
                <div class="row">
                    <div class="col-4" data-toggle="modal" data-target="#showInfo" @click="currentContact = contacts[index]">
                        {{ first_name }} {{ last_name }}
                    </div>
                    <div class="col-7" data-toggle="modal" data-target="#showInfo" @click="currentContact = contacts[index]">
                        <span v-if="phons[0]">{{ phons[0].phone }} </span>
                    </div>
                    <div class="col-1">
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#delete" @click="currentContact = contacts[index]" class="actions"><i class="fa fa-trash"></i></a>
                        <a href="javascript:void(0);" class="actions edit" data-toggle="modal" data-target="#edit" @click="currentContact = contacts[index]"><i class="fas fa-pencil-alt"></i></a>
                    </div>
                </div>
            </li>
        </ul>

        <!-- edit -->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTitle">Редактирование контакта</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-for="error in errors" v-bind:key="error" class="alert alert-danger">
                            {{ error[0] }}
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Имя" v-model="currentContact.first_name">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Фамилия" v-model="currentContact.last_name">
                            </div>
                        </div>

                        <div class="form-row mt-3" v-for="({phone}, index) in currentContact.phons" v-bind:key="index">
                            <div class="col-11">
                                <vue-phone-number-input 
                                    :id="'contact'+currentContact.id+'Phone'+index"
                                    @update="validationPhone;"
                                    default-country-code="UA" 
                                    :required="true" 
                                    :no-country-selector="true"
                                    :translations="{
                                        countrySelectorLabel: 'Код страны',
                                        countrySelectorError: 'Выберите страну',
                                        phoneNumberLabel: 'Номер телефона',
                                        example: 'Пример :'
                                    }"
                                    v-model="currentContact.phons[index].phone" />
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-sm btn-outline-danger mt-1" @click="currentContact.phons.splice(index, 1)"><i class="fa fa-trash"></i></button>
                            </div>                        
                        </div>

                        <a href="javascript:void(0);" class="btn btn-success mt-3" @click="currentContact.phons.push({'phone': ''})"><i class="fa fa-plus"></i> Добавить номер</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" @click="update()" id="btnEditSave">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- show -->
        <div class="modal fade" id="showInfo" tabindex="-1" role="dialog" aria-labelledby="showInfoTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showInfoTitle">
                            {{currentContact.first_name}}
                            {{currentContact.last_name}}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="сontact-data">Данные контакта</div>
                        <ul v-for="({phone}, index) in currentContact.phons" v-bind:key="index" class="phone-list">
                            <li><i class="fas fa-phone-alt"></i> <a :href="'tel:' + phone">{{ phone }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete -->
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTitle">
                            {{currentContact.first_name}}
                            {{currentContact.last_name}}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Удалить этот контакт?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="destroy(currentContact.id)">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input';

export default {
    data() {
        return {
            errors: [],
            contacts: [],
            currentContact: []
        }
    },
    created() {
       this.get();
    },
    methods: {
        validationPhone: function(payload) {
            if(!payload.isValid) {
                console.log('notValid');
            } 
        },
        get() {
            this.axios
                .get('/contacts')
                .then(response => {
                    this.contacts = response.data.data;
                });
        },
        update() {
            this.errors = [];

            this.axios
                .post('/contacts/update/' +  this.currentContact.id, this.currentContact)
                .then((response) => {
                    let i = this.contacts.map(item => item.id).indexOf(this.currentContact.id); // find index of your object
                    this.contacts[i] = this.currentContact;

                    $('#edit').modal('hide');
                })
                .catch((e) => {
                    this.errors = e.response.data.errors || 'Ошибка при редактировании контакта.';
                });
        },
        destroy(id) {
            this.axios
                .delete('/contacts/destroy/${id}')
                .then(response => {
                    let i = this.contacts.map(item => item.id).indexOf(id); // find index of your object
                    this.contacts.splice(i, 1)
                });
        }
    },
    components: {
        VuePhoneNumberInput
    }
}
</script>