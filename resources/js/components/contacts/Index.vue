<template>
    <div class="contacts">
        <div v-if="error" class="error">
            <p>{{ error }}</p>
        </div>

        <ul v-if="contacts" class="card">
            <li v-for="({ id, first_name, last_name, phons }, index) in contacts" v-bind:key="index" data-toggle="modal" data-target="#showInfo" @click="currentContact = contacts[index]">
                <div class="row">
                    <div class="col-4">
                        {{ first_name }} {{ last_name }}
                    </div>
                    <div class="col-4">
                        <span v-if="phons[0]">{{ phons[0].phone }} </span>
                    </div>
                    <div class="col-4">
                        <a href="javascript:void(0);" class="actions"><i class="fas fa-pencil-alt"></i></a>
                        <a href="javascript:void(0);" class="actions"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </li>
        </ul>

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
    </div>
</template>

<script>
export default {
    data() {
        return {
            contacts: [],
            currentContact: []
        }
    },
    created() {
       this.get();
    },
    methods: {
        get() {
            this.axios
                .get('/contacts')
                .then(response => {
                    this.contacts = response.data.data;
                });
        },
        delete(id) {
            this.axios
                .delete(`/contacts/delete/${id}`)
                .then(response => {
                    let i = this.contacts.map(item => item.id).indexOf(id); // find index of your object
                    this.contacts.splice(i, 1)
                });
        }
    }
}
</script>