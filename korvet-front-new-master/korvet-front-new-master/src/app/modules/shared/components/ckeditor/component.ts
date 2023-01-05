import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import {AuthTokenService} from 'src/app/api/auth/auth-token.service';
import {Urls} from '../../../../common/urls';

@Component({
  selector: 'app-ckeditor',
  templateUrl: './component.html',
  styleUrls: ['./component.css']
})
export class CkeditorComponent implements OnInit {
  @Output() changed: EventEmitter<any> = new EventEmitter();
  @Input() data: string;
  private token;
  private editor: any;

  constructor(
    private tokenService: AuthTokenService
  ) {
    this.token = this.tokenService.get();
  }

  ngOnInit() {
    DecoupledEditor.create(document.querySelector('#editor'), {
      ckfinder: {
        uploadUrl: Urls.api + 'uploaded-file/?command=QuickUpload&type=Files&responseType=json&access_token=' + this.token.access_token
      },
      heading: {
        options: [
          {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
          {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
          {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'}
        ]
      },
    }).then(editor => {
      console.log('Editor was initialized', editor);
      this.editor = editor;
      if (this.data) {
        this.editor.setData(this.data);
      }
      editor.model.document.on('change:data', (evt, data) => {
        this.changed.emit(this.editor);
      });
      document.querySelector('#toolbar-container').appendChild(editor.ui.view.toolbar.element);
    }).catch(error => {
      console.error(error);
    });
  }
}
