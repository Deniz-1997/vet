import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ListComponent as ProductExpenseListComponent} from './list/list.component';
import {ViewComponent as ProductExpenseViewComponent} from './view/view.component';
import {EditComponent as ProductExpenseEditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ExpenseRoutingModule} from './expense-routing.module';
import {ProductListModule} from '../../product-list/product-list.module';

@NgModule({
  declarations: [ProductExpenseListComponent, ProductExpenseViewComponent, ProductExpenseEditComponent],
  imports: [
    CommonModule,
    SharedModule,
    ExpenseRoutingModule,
    ProductListModule,
    ProductListModule
  ]
})

export class ExpenseModule {
}
