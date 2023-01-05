import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {FormControl} from '@angular/forms';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {MainComponent as TemplateComponent} from '../show-template/main.component';
import {AuthService} from '../../../../../../services/auth.service';
import {FilterUnitForByUserService} from '../../../../../../services/filter-unit-for-by-user.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-appointments-add-templates',
  templateUrl: './html.component.html'
})
export class MainComponent implements OnInit {
  @Output() addProductItems: EventEmitter<any> = new EventEmitter();

  crudType = CrudType;
  control = new FormControl('');
  appointmentTemplate$: Observable<{ id: number, fullName: string}[]>;
  appointmentTemplateFields = {0: 'id', 1: 'name', 2: 'unit'};
  productStockFields = {0: 'id', 1: 'name', 2: 'price', 3: 'measure', 4: 'quantity', 5: 'productStock'};
  limit = 500;
  private selectedValueName: string;
  filterUnitId: any;

  constructor(
    private store: Store<CrudState>,
    private dialog: MatDialog,
    protected getUnitIdService: FilterUnitForByUserService,
  ) {
    this.filterUnitId = getUnitIdService;
  }

  ngOnInit() {
    this.appointmentTemplate$ = this.store.pipe(select(getCrudModelData, {type: CrudType.AppointmentTemplate}));
  }

  addTemplateTo(type?) {
    if (this.control.value && this.control.value.id) {
      this.selectedValueName = this.control.value.name;
      this.store.dispatch(new LoadGetAction(
        {
          type: CrudType.AppointmentTemplate, params: this.control.value.id,
          onSuccess: (res) => {
            if (res.response.products) {
              this.setModel(type, res.response.products);
            }
            this.control.setValue(null);
          }
        }));
    }
  }

  setModel(type, products) {
    const idArray = [];
    products.map(product => {
      idArray.push(product.product.id);

      if (product.children && product.children.length > 0) {
        product.children.map(item => {
          idArray.push(item.product.id);
          return item;
        });
      }
      return product;
    });

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        filter: {
          id: idArray
        },
        fields: this.productStockFields
      },
      onSuccess: response => {
        if (response.response.items) {

          products.map(product => {
            response.response.items
              .map(j => {
                if (j.id === product.product.id) {
                  product.product.name = j.name;
                  product.measure = j.measure;
                  product.price = j.price;
                }

                if (product.stock && product.stock.id) {
                  j.productStock.map(
                    st => {
                      if (product.product.id === st.product.id && product.stock.id === st.stock.id) {
                        product.stock.name = st.stock.name;
                        product.product.balance = st.quantity;
                      }

                      return st;
                    }
                  );
                }


                return j;
              });


            if (product.children && product.children.length > 0) {
              product.children.map(item => {

                response.response.items
                  .map(j => {
                    if (j.id === item.product.id) {
                      item.product.name = j.name;
                      item.product.measure = j.measure;
                      item.price = j.price;
                    }

                    if (item.paymentObject && !item.product.paymentObject) {
                      item.product.paymentObject = {
                        code: item.paymentObject
                      };
                    }

                    if (item.stock && item.stock.id) {
                      j.productStock.map(
                        st => {
                          if (item.product.id === st.product.id && item.stock.id === st.stock.id) {
                            item.stock.name = st.stock.name;
                            item.product.balance = st.quantity;
                          }

                          return st;
                        }
                      );
                    }

                    return j;
                  });

                return item;
              });
            }
            return product;
          });

          const model = {
            productItemsWithChildren: products,
            productItemRow: products
          };
          if (type === 'add') {
            this.addProductItems.emit(model);
          } else {
            this.showTemplate(model, products);
          }
        }
      }
    }));
  }

  showTemplate(model, products) {

    const dialogRef = this.dialog.open(TemplateComponent, {
      data: {
        head: this.selectedValueName,
        products: products,
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--blue',
            action: true,
            title: 'Добавить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.addProductItems.emit(model);
      }
    });

  }

  hasTemplate(): boolean {
    return !(this.control.value instanceof Object);
  }

}
