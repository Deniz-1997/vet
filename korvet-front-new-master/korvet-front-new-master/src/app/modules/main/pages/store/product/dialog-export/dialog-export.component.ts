import {Component, OnInit} from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {DatePipe} from '@angular/common';
import {HttpClient} from '@angular/common/http';
import {AsyncStatus} from '../../../cash/cash.service';
import {Urls} from '../../../../../../common/urls';
import {NotifyService} from '../../../../../../services/notify.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-dialog-export',
  templateUrl: './dialog-export.component.html'
})
export class DialogExportComponent implements OnInit {
  today = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
  showError = false;
  loader = false;
  public formGroup: FormGroup;

  constructor(
    private datePipe: DatePipe,
    private http: HttpClient,
    public dialogRef: MatDialogRef<DialogExportComponent>,
    protected notify: NotifyService,
    protected router: Router
  ) {
  }

  ngOnInit() {
    this.formGroup = new FormGroup({
      dateFrom: new FormControl(this.today, [Validators.required]),
      timeFrom: new FormControl(null, [Validators.required]),
      dateTill: new FormControl(this.today, [Validators.required]),
      timeTill: new FormControl(null, [Validators.required]),
    });
  }

  submit() {
    if (this.formGroup.valid) {

      this.loader = true;
      const model = {...this.formGroup.value};

      model.dateFrom = model.dateFrom.split(' ')[0] + ' '
        + model.timeFrom + ':00';
      model.dateTill = model.dateTill.split(' ')[0] + ' '
        + model.timeTill + ':00';

      delete model['timeFrom'];
      delete model['timeTill'];

      this.http.post<AsyncStatus>(Urls.apiExportTo, model).subscribe(
        item => {
          if (item && item.status) {
            this.notify.handleMessage('Экспорт прошел успешно', 'success');

            if (item && item.response && item.response.length > 0) {
              this.router.navigate(['/store/ftp-history']).then();
            }
          }
          this.dialogRef.close();
          this.loader = false;
        },
        () => {
          this.loader = false;
        },
        () => this.loader = false
      );
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }
}
