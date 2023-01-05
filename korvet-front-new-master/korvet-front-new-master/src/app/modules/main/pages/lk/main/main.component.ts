import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../../../shared/components/modal-confirm/modal-confirm.component';
import {DataType} from '../../../../../common/data-type';
import {filter, take} from 'rxjs/operators';
import {CrudType} from '../../../../../common/crud-types';
import {UserAuthModel} from 'src/app/api/auth/auth.models';
import {AuthTokenService} from 'src/app/api/auth/auth-token.service';
import {AuthState} from 'src/app/api/auth/auth.reducer';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';
import {getUser} from 'src/app/api/auth/auth.selectors';
import {SetUserAction} from 'src/app/api/auth/auth.actions';
import {LoadCreateAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({selector: 'app-lk-main', templateUrl: './main.component.html'})

export class MainComponent implements OnInit {

  user$: Observable<UserAuthModel>;
  user: UserAuthModel;

  @ViewChild('photoInput')
  private photoInput: ElementRef;

  constructor(
    private tokenService: AuthTokenService,
    private store: Store<AuthState | CrudState>,
    private dialog: MatDialog,
  ) {
    this.user$ = store.pipe(select(getUser));
  }

  ngOnInit() {
    this.user$.subscribe(user => {
      this.user = user;
    });
    this.user$.pipe(filter(user => !!user), take(1)).subscribe(user => {
      this.store.pipe(select(getCrudModelStoreId, {
        type: CrudType.User,
        params: user.user.id
      })).subscribe(userModel => {
        if (userModel) {
          this.store.dispatch(new SetUserAction(new UserAuthModel({
            ...user,
            user: userModel,
          })));
        }
      });
    });
  }

  photoClick(e: Event): void {
    this.photoInput.nativeElement.click(e);
  }

  photoUpload(e: Event): void {
    const fileList: FileList = e.target['files'];
    const file: File = fileList.item(0);

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (event: Event) => {
      const dialog = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: 'Вы действительно хотите сменить фото?',
          body: '<img src="' + event.target['result'] + '" alt="" width="100%" height="100%">',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--gray',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right btn-st--red',
              action: true,
              title: 'Сохранить'
            },
          ],
        },
        maxHeight: '85vh',
        maxWidth: '85vw',
      });
      dialog.afterClosed().subscribe(answer => {
        if (answer) {
          this.store.dispatch(new LoadCreateAction({
            type: CrudType.UploadedFile,
            params: {file: file},
            dataType: DataType.formData,
            onSuccess: res => {
              this.store.dispatch(new LoadPatchAction({
                type: CrudType.User,
                params: <any>{
                  id: this.user.user.id,
                  additionalFields: {photoSrc: res.response.relativePath}
                },
              }));
            }
          }));
        }
      });
    };
  }

  getImgUser(): string {
    if (this.user.user.additionalFields && this.user.user.additionalFields.photoSrc) {
      const token = this.tokenService.get();
      return this.user.user.additionalFields.photoSrc + '?access_token=' + token.access_token;
    }
    return '/other-assets/img/avatar.png';
  }
}
