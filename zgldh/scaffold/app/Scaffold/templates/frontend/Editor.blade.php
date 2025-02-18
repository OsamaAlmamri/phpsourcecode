<?php
/**
 * @var $STARTER  \App\Scaffold\Installer\ModuleStarter
 * @var $MODEL  \App\Scaffold\Installer\Model\ModelDefinition
 * @var $field  \App\Scaffold\Installer\Model\Field
 */
$modelName = $STARTER->getModuleName();
$modelPascaleCase = $MODEL->getPascaleCase();
$modelSnakeCase = $MODEL->getSnakeCase();
$modelCamelCase = $MODEL->getCamelCase();
$frontendRoute = $MODEL->getFrontEndRoutePrefix();
$withRelationNames = join(',', $MODEL->getRelationNames());
$apiQueryParameters = $withRelationNames?"{_with: '{$withRelationNames}'}":'{}';
?>
<template>
  <el-row class="{{$modelSnakeCase}}-editor-page" v-loading="loading">
    <el-col :span="11">
      <editor-title :name="$t('{{$modelSnakeCase}}.title')"></editor-title>

      <el-form label-position="right" label-width="80px" :rules="rules" :model="form"
               ref="form">

        <form-item :label="$t('global.fields.id')" v-if="form.id">
          <el-input v-model="form.id" disabled></el-input>
        </form-item>
        @php
          use App\Scaffold\Installer\HtmlFields\BaseField;
          use App\Scaffold\Installer\Model\Field;
          $fields = $MODEL->getFields();
          foreach ($fields as $field) {
              /**
              * @var $field     Field
              * @var $baseField BaseField
              */
              $baseField = $field->getHtmlType();
              $fieldHtml = $baseField->html();
              echo $fieldHtml . "\n";
          }
        @endphp
        <form-item :label="$t('global.fields.created_at')" v-if="form.id">
          <el-input v-model="form.created_at" disabled></el-input>
        </form-item>

        <form-item>
          <el-button type="primary" @click="isCreating?onCreate():onUpdate()">
            @{{$t('global.terms.submit')}}
          </el-button>
          <el-button @click="$router.go(-1)">@{{$t('global.terms.back')}}</el-button>
        </form-item>
      </el-form>
    </el-col>
    <el-col :span="11" :offset="1"></el-col>
  </el-row>
</template>

<script type="javascript">
  import store  from '@/store'
  import { mapState } from 'vuex'
  import { SuccessMessage } from '@/utils/message'
  import { {{$modelPascaleCase}}Store, {{$modelPascaleCase}}Update, {{$modelPascaleCase}}Show } from '@/api/{{$modelCamelCase}}'
  import EditorMixin from '@/mixins/Editor'
  import { updateTitle } from '@/utils/browser'

  export default  {
    components: {},
    mixins: [EditorMixin],
    data () {
      return {
        rules: {},
        form: {!! json_encode($MODEL->getDefaultValues(), JSON_PRETTY_PRINT) !!}
      };
    },
    computed: {
      ...mapState({
//        items: state => state.model.items
      })
    },
//    beforeRouteEnter(to, from, next){
//      Preload store data here.
//      store.dispatch('user/LoadRoles').then(next);
//    },
    watch: {
      $route: 'fetchData',
    },
    mounted () {
      updateTitle('{{$modelSnakeCase}}.title')
      this.fetchData();
    },
    methods: {
      fetchData() {
        if (this.$route.params.id) {
          this.loading = true;
          {{$modelPascaleCase}}Show(this.$route.params.id, {!! $apiQueryParameters !!})
            .then(res => this.setFormData(res.data))
            .then(res => this.loading = false)
        }
      },
      setFormData(rawFormData){
        this.form = rawFormData
      },
      onCreate () {
        this.$refs.form.validate().then(valid => {
          this.loading = true;
          return {{$modelPascaleCase}}Store(this.form, {!! $apiQueryParameters !!});
        })
          .then(res => {
            this.loading = false;
            this.$router.replace({ path: `{{$frontendRoute}}/${res.data.id}/edit` });
          })
          .then(SuccessMessage(this.$t('global.terms.save_completed')))
          .catch(this.errorHandler);
      },
      onUpdate () {
        this.$refs.form.validate().then(valid => {
          this.loading = true;
          return {{$modelPascaleCase}}Update(this.form.id, this.form, {!! $apiQueryParameters !!})
        })
          .then(res => this.setFormData(res.data))
          .then(SuccessMessage(this.$t('global.terms.save_completed')))
          .then(res => this.loading = false)
          .catch(this.errorHandler);
      }
    }
  };
</script>

<style rel="stylesheet/scss" lang="scss">
  .{{$modelSnakeCase}}-editor-page{
    margin: 10px 30px;
  }
</style>
