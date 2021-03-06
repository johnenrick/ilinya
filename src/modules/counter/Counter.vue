<template>
  <div>
    <div class="row mb-5">
      <div class="col-sm-6 col-md-3 mb-2">
        <annoucement></annoucement>
      </div>
      <div class="col-sm-6 col-md-3 text-center">
        <button @click="showQueueCard" class="btn btn-lg btn-primary mb-2"><i class="fa fa-vcard-o" aria-hidden="true"></i> Create Queue Entry</button>
      </div>
      <div class="col-sm-12 col-md-3 ml-auto text-sm-right">
        <excel-export ref="excelExport" v-on:excel_export_clicked="excelExport"></excel-export>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <table-component
          ref="queueCardTable"
          :api="'queue_card'"
          :filter_setting="filterSetting"
          :column_setting="column_setting"
          :retrieve_parameter="retrieveParameter"
          v-on:row_clicked="rowClicked"
        >
        </table-component>
      </div>
    </div>
    <modal ref="queueCardManagement" v-on:change-state="queueCardModalChangeState">
      <div slot="header" style="width:100%">
        <i class="fa fa-vcard-o" aria-hidden="true"></i> Queue Card
        <span v-if="isLoadingCard" class="float-right"><i class="fa fa-hourglass-2" aria-hidden="true"></i> Please wait...</span>
      </div>
      <div slot="body">
        <div v-if="queue_card_id === -1" class="alert alert-danger text-center">
          <i class="fa fa-exclamation-circle" aria-hidden="true"></i> The Queue Card is not available. It may have been deleted by the user.
        </div>
        <form v-else ref="queueCardForm">
          <input type="hidden" name="id" v-bind:value="queue_card_id">
          <input-cell
            :input_name="'Form'"
            :db_name="'queue_form_id'"
            :input_type="'select'"
            :input_setting="formSelectInputSetting"
            :form_status="queue_card_id ? 'view' : formStatus"
            :default_value="0"
            :form_data="selected_queue_card"
            v-on:value_changed="formChanged"
          ></input-cell>
          <input-cell
            :input_name="'Number'"
            :db_name="'number'"
            :form_data="selected_queue_card"
            :form_status="formStatus"
          ></input-cell>
          <template v-for="(queueFormField, index) in queueFormFields">
            <input type="hidden" v-bind:name="'queue_card_fields['+index+'][id]'" v-bind:value="queueFormField['queue_card_field_id']">
            <input type="hidden" v-bind:name="'queue_card_fields['+index+'][queue_form_field_id]'" v-bind:value="queueFormField['id']">
            <input-cell
              :input_name="queueFormField['description']"
              :db_name="'queue_card_fields['+index+'][value]'"
              :default_value="queueFormField['value']"
            ></input-cell>
          </template>
          <div v-if="selected_queue_card['status'] === 3" class="alert alert-success text-center">
            This Q-card is done! <i class="fa fa-check-circle" aria-hidden="true"></i>
          </div>
          <div v-if="queue_card_id && selected_queue_card['status'] !== 3 && !isLoadingCard" class="row">
            <div class="col-sm-12 text-center">
              <button @click="removeQueueCard" type="button" class="btn btn-outline-danger pull-left "> <i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
              <button v-if="selected_queue_card.facebook_user_id" v-bind:disabled="isCalling" @click="selected_queue_card.facebook_user ? pageUser(selected_queue_card.facebook_user.account_number, 'Please come to the counter') : null" type="button" class="btn btn-primary "><i class="fa fa-bell-o" aria-hidden="true"></i> Call </button>
              <button v-if="selected_queue_card['status'] === 2" @click="changeQueueCardStatus(1)" type="button" class="btn btn-warning">Cancel Serving</button>
              <button v-else @click="changeQueueCardStatus(2)" type="button" class="btn btn-warning "> Serve</button>
              <button v-if="selected_queue_card['status'] === 2" @click="changeQueueCardStatus(3)" type="button" class="btn btn-success "><i class="fa fa-check-circle-o" aria-hidden="true"></i> Finish</button>
              <button @click="submitQueueCard" type="button" class="btn btn-outline-success pull-right"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
            </div>
          </div>
          <div v-else-if="selected_queue_card['status'] !== 3  && !isLoadingCard" class="row">
            <div class="col-sm-12 text-right">
              <span v-if="formStatus === 'success'" class="text text-success">Success!</span>
              <span v-else-if="formStatus === 'error'" class="text text-danger">Failed!</span>
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
  import CONFIG from '../../config'
  import AUTH from '../../services/auth'
  export default{
    name: '',
    components: {
      'excel-export': require('./ExcelExport.vue'),
      'table-component': require('components/table/TableComponent.vue'),
      'modal': require('components/modal/Modal.vue'),
      'input-cell': require('components/input_field/InputCell.vue'),
      'annoucement': require('./Announcement.vue')
    },
    create(){

    },
    mounted(){
    },
    data(){
      let formSelectInputSetting = {
        option_function: (instance) => {
          this.APIRequest('queue_form/retrieve', {condition: [{column: 'company_id', value: AUTH.user.company_id}]}, (response) => {
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
      let filterSetting = {}
      filterSetting = {
        queue_form_id: {
          label: 'Form',
          input_type: 'select',
          input_setting: {
            option_function: (instance) => {
              this.APIRequest('queue_form/retrieve', {condition: [{column: 'company_id', value: AUTH.user.company_id}]}, (response) => {
                if(response['data']){
                  let options = []
                  options.push({
                    value: null,
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
        },
        id: {
          label: 'Q-Card Number'
        },
        number: {
        },
        status: {
          input_type: 'hidden',
          default_value: 3,
          clause: '!='
        },
        status_dummy: {
          is_dummy: true,
          label: 'status',
          input_type: 'select',
          clause: '!=',
          default_value: 4,
          input_setting: {
            options: [
              {value: null, label: 'Any'},
              {value: 4, label: 'Active'},
              {value: 1, label: 'On Queue'},
              {value: 2, label: 'Serving'},
              {value: 3, label: 'Finished'}
            ]
          },
          value_function: (form) => {
            let newForm = {
            }
            if(form){
              if(form.status_dummy * 1 === 4){
                this.filterSetting['status']['clause'] = '!='
                newForm.status = 3
              }else{
                this.filterSetting['status']['clause'] = '='
                newForm.status = form.status_dummy
              }
            }
            return newForm
          }
        },
        start_date_filter: {
          db_name: 'created_at',
          input_type: 'date',
          clause: '>=',
          label: 'Start Date',
          input_setting: {
            with_time: true
          }
        },
        end_date_filter: {
          db_name: 'created_at',
          input_type: 'date',
          clause: '<',
          label: 'End Date',
          input_setting: {
            time_setting: {
              default_time: '23:59:59'
            }
          }
        }

      }
      return {
        isLoadingCard: false,
        queue_card_id: 0,
        rowIndex: -1,
        selected_queue_card: {

        },
        filterSetting: filterSetting,
        column_setting: {
          id: {},
          number: {},
          details: {
            sort: false,
            type: 'html',
            value_function: (row) => {
              let summary = ''
              if(row['queue_card_fields']){
                for(let x in row['queue_card_fields']){
                  summary += '<div class="col-sm-6 col-md-4"><strong>' + row['queue_card_fields'][x]['queue_form_field']['description'] + ': </strong>' + row['queue_card_fields'][x]['value'] + '</div>'
                }
              }
              return '<small><div class="row">' + summary + '</div></small>'
            }
          },
          created_at: {

          },
          status: {
            type: 'html',
            value_function: (row) => {
              let status = [
                '<span class="badge badge-primary">ON QUEUE ' + '</span>',
                '<span class="badge badge-warning">SERVING</span>',
                '<span class="badge badge-success">FINISHED</span>']
              return status[row['status'] - 1]
            }
          },
          // call: {
          //   type: 'button',
          //   if_condition: (row) => {
          //     return row['facebook_user_id'] && row['status'] !== 3
          //   },
          //   setting: {
          //     on_click: (event, row) => {
          //       $(event.target).attr('disabled', true)
          //       this.pageUser(row['facebook_user']['account_number'], 'Please come to the counter', () => {
          //         $(event.target).attr('disabled', false)
          //       })
          //     },
          //     class: 'btn-primary btn-sm',
          //     label: '<i class="fa fa-bell-o" aria-hidden="true"></i> Call'
          //   }
          // },
          action: {
            name: 'Action',
            type: 'multiple-button',
            setting: {
              buttons: [{
                if_condition: (row) => {
                  return row['facebook_user_id'] && row['status'] !== 3
                },
                on_click: (event, row) => {
                  $(event.target).attr('disabled', true)
                  this.pageUser(row['facebook_user']['account_number'], 'Please come to the counter', () => {
                    $(event.target).attr('disabled', false)
                  })
                },
                class: 'btn-primary btn-sm',
                label: '<i class="fa fa-bell-o" aria-hidden="true"></i> Call'
              }, {
                if_condition: (row) => {
                  return true
                },
                on_click: (event, row, rowIndex) => {
                  $(event.target).attr('disabled', true)
                  this.APIRequest('queue_card/delete', {id: row['id']}, (response) => {
                    if(response['data']){
                      this.$refs.queueCardTable.deleteRow(rowIndex)
                      if(row.facebook_user){
                        this.pageUser(row.facebook_user.account_number, 'You QCard: ' + row['id'] + ' has been removed')
                      }
                      $(event.target).attr('disabled', false)
                    }
                  })
                },
                class: 'btn-outline-danger btn-sm',
                label: '<i class="fa fa-trash-o" aria-hidden="true"></i> Remove'
              }]
            }
          }
        },
        tableExportSetting: {
          file_name: 'QueueCards',
          column_setting: {
            id: {
              column_name: '#',
              value_function: (entryData, rowIndex) => {
                return rowIndex + 1
              }
            },
            created_at: {}
          }
        },
        retrieveParameter: {
          condition: [{
            column: 'company_id',
            value: AUTH.user.company_id
          }],
          with_foreign_table: [
            'facebook_user',
            'queue_card_fields'
          ]
        },
        formStatus: 'close',
        formSelectInputSetting: formSelectInputSetting,
        queueFormFields: [],
        isCalling: false
      }
    },
    props: {
    },
    methods: {
      excelExport(){
        this.$refs.excelExport.exportTable(this.$refs.queueCardTable.getFiter(), () => {

        })
      },
      pageUser(accountNumber, message, callback, finish){
        this.isCalling = true
        finish = (typeof finish === 'undefined') ? 0 : finish
        this.APIGetRequest(CONFIG.BACKEND_URL + '/bot/reminder/' + accountNumber + '/' + message + '/' + finish, {}, (response) => {
          this.isCalling = false
          if(callback){
            callback()
          }
        })
      },
      removeQueueCard(){
        this.APIRequest('queue_card/delete', {id: this.queue_card_id}, (response) => {
          if(response['data']){
            this.$refs.queueCardTable.deleteRow(this.rowIndex)
            if(this.selected_queue_card.facebook_user){
              this.pageUser(this.selected_queue_card.facebook_user.account_number, 'You QCard: ' + this.queue_card_id + ' has been removed')
            }
            this.closeQueueCardModal()
          }
        })
      },
      changeQueueCardStatus(status){
        let requestParam = {
          id: this.queue_card_id,
          status: status
        }
        if(status * 1 === 2 && this.selected_queue_card.facebook_user){
          this.pageUser(this.selected_queue_card.facebook_user.account_number, "It's your turn!")
        }
        this.APIRequest('queue_card/update', requestParam, (response) => {
          if(response['data']){
            this.selected_queue_card['status'] = status
            if(status * 1 === 2 || status * 1 === 1){
              this.$refs.queueCardTable.updateRow(this.rowIndex, this.queue_card_id)
            }else{
              this.$refs.queueCardTable.deleteRow(this.rowIndex)
            }
            if(status * 1 === 3 && this.selected_queue_card.facebook_user){
              this.pageUser(this.selected_queue_card.facebook_user.account_number, 'Thank you!', null, 1)
            }
          }
        })
      },
      rowClicked(index, entryID){
        this.queue_card_id = 0
        this.isLoadingCard = true
        this.showQueueCard(0)
        this.rowIndex = index
        let requestoption = {
          id: entryID,
          with_foreign_table: [
            'queue_card_fields',
            'facebook_user'
          ]
        }
        this.APIRequest('queue_card/retrieve', requestoption, (response) => {
          if(response['data']){
            this.queue_card_id = response['data']['id']
            this.selected_queue_card = response['data']
            let queueCardField = response['data']['queue_card_fields']
            // retrieve queue form fields
            let queueFormRequestCondition = {
              condition: [{
                column: 'queue_form_id',
                value: response['data']['queue_form_id']
              }],
              sort: {
                sequence: 'asc'
              }
            }
            this.APIRequest('queue_form_field/retrieve', queueFormRequestCondition, (queueFormResponse) => {
              if(queueFormResponse['data']){
                this.queueFormFields = queueFormResponse['data']
                for(let queueFormFieldX = 0; queueFormFieldX < queueFormResponse['data'].length; queueFormFieldX++){
                  this.queueFormFields[queueFormFieldX]['queue_card_field_id'] = null
                  this.queueFormFields[queueFormFieldX]['value'] = ''
                  for(let x = 0; x < queueCardField.length; x++){
                    if(queueCardField[x]['queue_form_field_id'] * 1 === queueFormResponse['data'][queueFormFieldX]['id'] * 1){
                      // this.queueFormFields[x]['description'] = this.queueFormFields[x]['queue_form_field']['description']
                      this.queueFormFields[queueFormFieldX]['value'] = queueCardField[x]['value']
                      this.queueFormFields[queueFormFieldX]['queue_card_field_id'] = queueCardField[x]['id']
                    }
                  }
                }
              }
            })

            this.formStatus = (response['data']['status'] * 1 === 3) ? 'view' : 'editing'
            // this.showQueueCard(this.queue_card_id)

          }else{
            this.queue_card_id = -1
            this.$refs.queueCardTable.deleteRow(index)
          }
          this.isLoadingCard = false
        })
      },
      submitQueueCard(){
        this.isLoadingCard = true
        let link = this.queue_card_id ? 'queue_card/update' : 'queue_card/create'
        this.APIFormRequest(link, this.$refs.queueCardForm, (response) => {
          let prevFormStatus = this.formStatus
          if(response['data']){
            this.formStatus = 'success'

            if(!this.queue_card_id){
              this.$refs.queueCardTable.retrieveData()
              this.closeQueueCardModal()
            }
          }else{
            this.formStatus = 'error'
          }
          setTimeout(() => {
            this.formStatus = prevFormStatus
          }, 2000)
          this.isLoadingCard = false
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
          this.$refs.queueCardTable.retrieveData(this.$refs.queueCardTable.prevRetrieveType)
          this.formStatus = 'close'
        }
      },
      showQueueCard(queueCardID){
        if(typeof queueCardID !== 'number'){
          this.queue_card_id = 0
          this.formStatus = 'create'
          this.selected_queue_card = {
            queue_form_id: 0
          }
          this.queueFormFields = []
        }
        this.$refs.queueCardManagement.showModal()
      }
    }
  }
</script>
<style scoped>

</style>
