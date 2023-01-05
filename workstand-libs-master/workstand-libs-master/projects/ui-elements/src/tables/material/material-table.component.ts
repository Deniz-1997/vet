import {ChangeDetectionStrategy, Component, ViewEncapsulation} from '@angular/core';
import {PluckPipe} from 'ngx-pipes';
import {MainTable} from "../table";

@Component({
  selector: 'k-material-table',
  templateUrl: './material-table.component.html',
  styleUrls: ['../table.css'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class MaterialTableComponent extends MainTable {

}
