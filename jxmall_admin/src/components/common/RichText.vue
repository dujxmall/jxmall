<template>
  <div>
    <div style="margin-bottom: 100px;">
      <quill-editor
        style="height: auto;flex-wrap: wrap;word-wrap: break-word;word-break: break-word;width:750px;height: 450px;"
        ref="QuillEditor" v-model="detail" class="myQuillEditor ql-editor" :options="editorOption"
        @change="onEditorChange($event)" />
    </div>
    <file-picker :key="randomString(3)" @selected="handleSuccess" :multiple='true'><span id="file-picker"
        class="quill-img"></span>
    </file-picker>
  </div>
</template>

<script>
import {
  quillEditor,
  Quill
} from 'vue-quill-editor'
import imageResize from 'quill-image-resize-module' //调节图片大小
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

Quill.register('modules/imageResize', imageResize)
import FilePicker from '@/components/common/FilePicker'

const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'], // toggled buttons
  ['blockquote', 'code-block'],
  [{
    'header': 1
  }, {
    'header': 2
  }], // custom button values
  [{
    'list': 'ordered'
  }, {
    'list': 'bullet'
  }],
  [{
    'script': 'sub'
  }, {
    'script': 'super'
  }], // superscript/subscript
  [{
    'indent': '-1'
  }, {
    'indent': '+1'
  }], // outdent/indent
  [{
    'direction': 'rtl'
  }], // text direction

  [{
    'size': ['small', false, 'large', 'huge']
  }], // custom dropdown
  [{
    'header': [1, 2, 3, 4, 5, 6, false]
  }],

  [{
    'color': []
  }, {
    'background': []
  }], // dropdown with defaults from theme
  [{
    'font': []
  }],
  [{
    'align': []
  }],
  ['link', 'image'],
  ['clean'] // remove formatting button
]
export default {
  name: 'RichText',
  components: {
    quillEditor,
    FilePicker
  },
  props: {
    quillIndex: {
      type: Number,
      default: 0
    },
    content: {
      type: String,
      default: ''
    }
  },
  data() {
    // 工具栏配置
    let self = this;
    return {
      detail: '',
      showFilePicker: true,
      editorOption: {
        modules: {
          toolbar: {
            container: toolbarOptions, // 工具栏
            handlers: {
              'image': function (value) {
                if (value) {


                  const index = self.quillIndex;

                  document.querySelectorAll(".quill-img")[index].click();

                  //document.getElementById('file-picker').click()
                } else {
                  self.quill.format('image', false);
                }
              }
            }
          },
          imageResize: {
            displayStyles: {
              backgroundColor: 'black',
              border: 'none',
              color: 'white'
            },
            modules: ['Resize', 'DisplaySize', 'Toolbar']
          },
        }
      }
    }
  },
  created() {

  },
  watch: {
    content(newVal, oldVal) {
      this.detail = newVal

    }
  },
  methods: {
    randomString(len) {
      //默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
      var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
      var tempLen = chars.length,
        tempStr = '';
      for (var i = 0; i < len; ++i) {
        tempStr += chars.charAt(Math.floor(Math.random() * tempLen));
      }
      return tempStr;
    },
    onEditorChange(e) {
      this.$emit('contentChange', {
        'content': this.detail
      })
    },
    handleSuccess(res) {
      // 获取富文本组件实例
      let quill = this.$refs.QuillEditor.quill
      // 如果上传成功
      if (res) {
        let length = 100;
        if (quill.getSelection()) {
          length = quill.getSelection().index;
        }
        for (let i in res) {
          length++;
          // 插入图片，res为服务器返回的图片链接地址
          quill.insertEmbed(length, 'image', res[i].url)
          // 调整光标到最后
          quill.setSelection(length + 1)
        }
        // 获取光标所在位置
      } else {
        // 提示信息，需引入Message
        Message.error('图片插入失败')
      }
    },
  }
}
</script>


<style lang="scss">
.ql-formats {
  line-height: normal;
}

.edit_container,
.quill-editor {
  height: 300px;
}

.ql-snow .ql-picker.ql-size .ql-picker-label::before,
.ql-snow .ql-picker.ql-size .ql-picker-item::before {
  content: "14px";
}

.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="small"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="small"]::before {
  content: "10px";
}

.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="large"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="large"]::before {
  content: "18px";
}

.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="huge"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="huge"]::before {
  content: "32px";
}

.ql-snow .ql-picker.ql-header .ql-picker-label::before,
.ql-snow .ql-picker.ql-header .ql-picker-item::before {
  content: "文本";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before {
  content: "标题1";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before {
  content: "标题2";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before {
  content: "标题3";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="4"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="4"]::before {
  content: "标题4";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="5"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="5"]::before {
  content: "标题5";
}

.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="6"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="6"]::before {
  content: "标题6";
}

.ql-snow .ql-picker.ql-font .ql-picker-label::before,
.ql-snow .ql-picker.ql-font .ql-picker-item::before {
  content: "标准字体";
}

.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="serif"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="serif"]::before {
  content: "衬线字体";
}

.ql-snow .ql-picker.ql-font .ql-picker-label[data-value="monospace"]::before,
.ql-snow .ql-picker.ql-font .ql-picker-item[data-value="monospace"]::before {
  content: "等宽字体";
}
</style>
