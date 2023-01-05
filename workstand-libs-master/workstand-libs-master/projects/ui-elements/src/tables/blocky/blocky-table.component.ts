import {ChangeDetectionStrategy, Component, ViewEncapsulation} from '@angular/core';
import {PluckPipe} from 'ngx-pipes';
import {MainTable} from "../table";

@Component({
  selector: 'k-blocky-table',
  templateUrl: './blocky-table.component.html',
  styleUrls: ['../table.css'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class BlockyTableComponent extends MainTable {

}
