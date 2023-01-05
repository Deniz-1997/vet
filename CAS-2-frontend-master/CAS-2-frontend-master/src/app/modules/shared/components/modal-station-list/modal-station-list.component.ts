import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ReferenceStationModel} from '../../../../models/reference/reference.station.models';
import {ReferenceBusinessEntityModel} from '../../../../models/reference/reference.businessEntity.models';
import {SessionStorageService} from '../../../../services/sessionStorage.service';
import {UserObjectListService} from '../../../../services/user-object-list.service';

type TypeButton = 'raised' | 'stroked';

@Component({
  templateUrl: './modal-station-list.component.html',
  styleUrls: ['./modal-station-list.component.scss'],
})
export class ModalStationListComponent implements OnInit {
  stationListConvert: Array<any> = [];
  buttonName = 'Выбрать станции';
  buttonType: TypeButton = 'raised';

  constructor(
    public dialog: MatDialog,
    @Inject(MAT_DIALOG_DATA) public data: Array<ReferenceStationModel | ReferenceBusinessEntityModel>,
    private dialogRef: MatDialogRef<ModalStationListComponent>,
    private sessionStorageService: SessionStorageService,
    private userObjectService: UserObjectListService,
  ) {
  }
  ngOnInit(): void {
    this.stationListConvert = this.data;
    if (!this.stationListConvert.length) {
      this.buttonName = 'Закрыть';
      this.buttonType = 'stroked';
    }
  }



  private changeCheckedValueInStationList(id: number, checked: boolean): boolean | void {
    for (const idx in this.stationListConvert) {
      if (this.stationListConvert[idx].id === id) {
        return this.stationListConvert[idx].checked = checked;
      }
    }
  }

  public changeChildrenCheckedValue(checked: boolean, station: any): void {
    station.checked = checked;
    for (const level in station.children) {
      if (station.children.hasOwnProperty(level)) {
        this.changeCheckedValueInStationList(station.children[level].id, checked);
        for (const level_two in station.children[level].children) {
          if (station.children[level].children.hasOwnProperty(level_two)) {
            this.changeCheckedValueInStationList(station.children[level].children[level_two].id, checked);
            for (const level_three in station.children[level].children[level_two].children) {
              if (station.children[level].children[level_two].children.hasOwnProperty(level_three)) {
                this.changeCheckedValueInStationList(station.children[level].children[level_two].children[level_three].id, checked);
              }
            }
          }
        }
      }
    }
    this.sessionStorageService.saveToSessionStorageObject('station', this.stationListConvert);
    this.userObjectService.setListStations(this.stationListConvert);
  }

  close(): void {
    this.dialogRef.close();
  }

}
