import {Injectable} from '@angular/core';
import {ReferenceStationModel} from '../models/reference/reference.station.models';

@Injectable({
  providedIn: 'root'
})
export class StationListConvertService {
  protected  _stationListConvert = new Array<any>();
  public stationList = new Array<ReferenceStationModel>();

  get stationListConvert(): Array<any> {
    return this._stationListConvert;
  }

  set stationListConvert(val: Array<any>) {
    this._stationListConvert = [];
    val.forEach(i => {
      i.padding = {'padding-left': '20px'};
      this._stationListConvert.push(i);
      i.children.forEach(a => {
        a.padding = {'padding-left': '40px'};
        this._stationListConvert.push(a);
        a.children.forEach(b => {
          b.padding = {'padding-left': '60px'};
          this._stationListConvert.push(b);
          b.children.forEach(c => {
            c.padding = {'padding-left': '80px'};
            this._stationListConvert.push(c);
          });
        });
      });
    });
  }

  formatListStation(parent: ReferenceStationModel): any {
    let treeList: any;
    if (parent) {
      treeList = this.stationList.filter(n => n.parent != null && n.parent.id === parent.id);
    } else {
      treeList = this.stationList.filter(val => val.parent === null);
    }
    for (const i in treeList) {
      if (treeList.hasOwnProperty(i)) {
        treeList[i]['children'] = this.formatListStation(treeList[i]);
      }
    }
    return treeList;
  }

  selectedValues(value: any): Array<any> {
    const selectedValues: Array<any> = [];
    value.forEach(children_1 => {
      selectedValues.push(children_1);
      children_1.children.forEach(children_2 => {
        selectedValues.push(children_2);
        children_2.children.forEach(children_3 => {
          selectedValues.push(children_3);
          children_3.children.forEach(children_4 => {
            selectedValues.push(children_4);
          });
        });
      });
    });
    return selectedValues;
  }

}
