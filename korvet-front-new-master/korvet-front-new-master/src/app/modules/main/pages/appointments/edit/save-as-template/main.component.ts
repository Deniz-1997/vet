import {Component, Input, OnInit} from '@angular/core';
import {ModalSimpleFormComponent} from '../../../../../shared/components/modal-simple-form/modal-simple-form.component';
import {CrudType} from '../../../../../../common/crud-types';
import {MatDialog} from '@angular/material/dialog';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-appointments-save-as-template',
  templateUrl: './html.component.html'
})
export class MainComponent implements OnInit {
  @Input() productItems;
  @Input() unitId: number;
  constructor(
    private dialog: MatDialog,
    private store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
  }

  saveAsTemplate() {
    const dialogRef = this.dialog.open(ModalSimpleFormComponent, {
      width: window.innerWidth > 960 ? '25%' : '90%',
      height: '100% - 50px',
      data: {
        head: 'Введите название шаблона',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--blue',
            action: true,
            title: 'Сохранить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {

        const productItems = {...this.productItems};
        const model = {products: [], name: result, unit: [{id: this.unitId}]};

        if (productItems.value.length > 0) {
          for (const i in productItems.value) {
            if (productItems.value[i]) {
              const modelItems = [];

              if (productItems.value[i].items.length > 0) {
                for (const j in productItems.value[i].items) {
                  if (productItems.value[i].items[j]) {

                    modelItems.push({
                      product: {
                        id: productItems.value[i].items[j].product.id,
                        name: productItems.value[i].items[j].product.name,
                        paymentObject: {
                          code: productItems.value[i].items[j].paymentObject,
                        },
                      },

                      quantity: productItems.value[i].items[j].quantity,
                      stock: productItems.value[i].items[j].stock
                    });
                  }
                }
              }

              model.products.push({
                product: {
                  id: productItems.value[i].product.id,
                  name: productItems.value[i].product.name,
                  paymentObject: {
                    code: productItems.value[i].paymentObject
                  },
                },

                quantity: productItems.value[i].quantity,
                stock: productItems.value[i].stock,
                children: modelItems
              });
            }
          }
        }

        model.name = result;
        model.unit[0].id = this.unitId;

        this.store.dispatch(new LoadCreateAction({
          type: CrudType.AppointmentTemplate,
          params: model
        }));

      }
    });
  }

}
