<template>
  <div>
    <div class="row">
      <div class="col-sm-6 text-center"></div>
      <div class="col-sm-6">
        <button @click="showQueueCard" class="btn btn-lg btn-primary"><i class="fa fa-vcard-o" aria-hidden="true"></i> Create Queue Entry</button>
      </div>
    </div>
    <table-component
      ref="queueCardTable"
      :api="'queue_card'"
      :filter_setting="filterSetting"
      :column_setting="column_setting"
      v-on:row_clicked="rowClicked"
    >
    </table-component>

    <modal ref="queueCardManagement" v-on:change-state="queueCardModalChangeState">
      <div slot="header">
        <i class="fa fa-vcard-o" aria-hidden="true"></i> Queue Card
      </div>
      <div slot="body">
        <form ref="queueCardForm">
          <input-cell
            :input_name="'Form'"
            :db_name="'queue_form_id'"
            :input_type="'select'"
            :input_setting="formSelectInputSetting"
            :form_status="queue_card_id ? 'view' : formStatus"
            :default_value="0"
            :form_data="select_queue_card"
            v-on:value_changed="formChanged"
          ></input-cell>
          <input-cell
            :input_name="'Number'"
            :db_name="'number'"
            :form_data="select_queue_card"
          ></input-cell>
          <template v-for="(queueFormField, index) in queueFormFields">
            <input type="hidden" v-bind:name="'queue_card_fields['+index+'][queue_form_field_id]'" v-bind:value="queueFormField['id']">
            <input-cell
              :input_name="queueFormField['description']"
              :db_name="'queue_card_fields['+index+'][value]'"
              :default_value="queueFormField['value']"
            ></input-cell>
          </template>
          <div v-if="queue_card_id" class="row">
            <div class="col-sm-12 text-center">
              <button v-bind:disabled="select_queue_card['status'] === 2"@click="changeQueueCardStatus(2)" type="button" class="btn btn-warning "><i class="fa fa-bell-o" aria-hidden="true"></i> {{select_queue_card['status'] === 2 ? 'Serving' : 'Serve'}}</button>
              <button @click="changeQueueCardStatus(3)" type="button" class="btn btn-success "><i class="fa fa-check-circle-o" aria-hidden="true"></i> Finish</button>
            </div>
          </div>
          <div v-else class="row">
            <div class="col-sm-12 text-right">
              <button @click="submitQueueCard" type="button" class="btn btn-primary ">Submit</button>
              <button @click="closeQueueCardModal" type="button" class="btn btn-default">Close</button>
            </div>
          </div>
        </form>
      </div>
    </modal>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'table-component': require('components/table/TableComponent.vue'),
      'modal': require('components/modal/Modal.vue'),
      'input-cell': require('components/input_field/InputCell.vue')
    },
    create(){

    },
    mounted(){

    },
    data(){
      let formSelectInputSetting = {
        option_function: (instance) => {
          this.APIRequest('queue_form/retrieve', {}, (response) => {
            if(response['data']){
              let options = []
              options.push({
                value: 0,
                label: 'Select Form'
              })
              for(let x = 0; x < response['data'].length; x++){
                options.push({
                  value: response['data'][x]['id'],
                  label: response['data'][x]['title']
                })
              }
              instance.setOption(options)
            }
          })
        }
      }
      return {
        queue_card_id: 0,
        rowIndex: -1,
        select_queue_card: {

        },
        filterSetting: {
          number: {},
          status: {
            input_type: 'select',
            input_setting: {
              options: [
                {value: null, label: 'Any'},
                {value: 1, label: 'On Queue'},
                {value: 2, label: 'Serving'},
                {value: 3, label: 'Finished'}
              ]
            }
          }
        },
        column_setting: {
          number: {},
          status: {
            type: 'html',
            value_function: (row) => {
              let status = [
                '<span class="badge badge-primary">ON QUEUE</span>',
                '<span class="badge badge-warning">SERVING</span>',
                '<span class="badge badge-success">FINISHED</span>']
              return status[row['status'] - 1]
            }
          }
        },
        retrieveParameter: {
          with_foreign_table: [
            'facebook_user'
          ]
        },
        formStatus: 'close',
        formSelectInputSetting: formSelectInputSetting,
        queueFormFields: []
      }
    },
    props: {
    },
    methods: {
      changeQueueCardStatus(status){
        let requestParam = {
          id: this.queue_card_id,
          status: status
        }
        this.APIRequest('queue_card/update', requestParam, (response) => {
          if(response['data']){
            console.log(status)
            if(status * 1 === 2){
              this.$refs.queueCardTable.updateRow(this.rowIndex, this.queue_card_id)
            }else{
              this.$refs.queueCardTable.deleteRow(this.rowIndex)
            }
          }
        })
      },
      rowClicked(index, entryID){
        this.rowIndex = index
        let requestoption = {
          id: entryID,
          with_foreign_table: [
            'queue_card_fields'
          ]
        }
        this.APIRequest('queue_card/retrieve', requestoption, (response) => {
          if(response['data']){
            this.queue_card_id = response['data']['id']
            this.select_queue_card = response['data']
            this.queueFormFields = response['data']['queue_card_fields']
            for(let x = 0; x < this.queueFormFields.length; x++){
              this.queueFormFields[x]['description'] = this.queueFormFields[x]['queue_form_field']['description']
              this.queueFormFields[x]['queue_card_field_id'] = this.queueFormFields[x]['id']
              this.queueFormFields[x]['id'] = this.queueFormFields[x]['queue_form_field_id']
            }
            this.showQueueCard(this.queue_card_id)

          }
        })
      },
      submitQueueCard(){
        let link = this.queue_card_id ? 'queue_card/update' : 'queue_card/create'
        this.APIFormRequest(link, this.$refs.queueCardForm, (response) => {
          this.$refs.queueCardTable.retrieveData()
          this.closeQueueCardModal()
        })
      },
      closeQueueCardModal(){
        this.$refs.queueCardManagement.closeModal()
        this.formStatus = 'close'
      },
      formChanged(e, queueFormID){
        let requestOption = {
          condition: [
            {
              column: 'queue_form_id',
              value: e ? $(e.target).val() : queueFormID
            }
          ]
        }
        this.APIRequest('queue_form_field/retrieve', requestOption, (response) => {
          if(response['data']){
            this.queueFormFields = response['data']
          }else{
            this.queueFormFields = []
          }
        })
      },
      queueCardModalChangeState(value){
        if(!value){
          this.formStatus = 'close'
        }
      },
      showQueueCard(queueCardID){
        if(typeof queueCardID !== 'number'){
          this.queue_card_id = 0
          this.formStatus = 'create'
        }
        this.$refs.queueCardManagement.showModal()

      }
    }

  }
</script>
<style scoped>

</style>
