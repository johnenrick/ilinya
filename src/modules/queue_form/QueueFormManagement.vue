<template>
  <div>
    <module :api="api" :table_setting="table_setting" :form_setting="form_setting"></module>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'module': require('components/common_module/CommonModule.vue')
    },
    create(){
    },
    mounted(){
    },
    data(){
      let columnSetting = {
        title: {}
      }
      let tableSetting = {
        columnSetting: columnSetting
      }
      let formSetting = {
        modal_size: 'modal-lg',
        retrieveParameter: {
          with_foreign_table: [
            'queue_form_fields'
          ]
        },
        inputs: {
          title: {},
          queue_form_fields: {
            label_colspan: 12,
            input_name: 'Form Fields',
            input_type: 'table-input',
            input_setting: {
              column_setting: {
                description: {
                },
                type: {

                },
                additional_option: {
                  db_name: 'setting'
                },
                main_field: {
                  db_name: 'on_summary',
                  input_type: 'select',
                  default_value: 1,
                  select_option: [
                    {
                      value: 1,
                      label: 'Yes'
                    },
                    {
                      value: 2,
                      label: 'No'
                    }
                  ]
                }
              }
            }
          }
        }
      }
      return {
        api: 'queue_form',
        table_setting: tableSetting,
        form_setting: formSetting
      }
    },
    props: {
    },
    methods: {
    }

  }
</script>
<style scoped>

</style>
