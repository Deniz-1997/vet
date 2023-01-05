import {Component, Input, OnInit} from '@angular/core';
import {WildAnimalModel} from '../../../../../../models/wild/wild-animal.models';
import {Router} from '@angular/router';
import {OwlOptions} from 'ngx-owl-carousel-o';
import {ModalGallseryComponent} from '../../../../../shared/components/modal-gallery/modal-gallsery.component';
import {MatDialog} from '@angular/material/dialog';

@Component({
  selector: 'app-wild-animal-card-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit {
  @Input() item: WildAnimalModel;
  @Input() loading: boolean;
  @Input() visibleFooter = false;

  customOptions: OwlOptions = {
    loop: true,
    mouseDrag: false,
    touchDrag: false,
    pullDrag: false,
    dots: false,
    navSpeed: 700,
    navText: ['<', '>'],
    items: 1,
    nav: true
  };

  constructor(
    protected router: Router,
    private dialog: MatDialog
  ) {
  }

  ngOnInit() {
  }

  public getGender(val: string): string {
    switch (val) {
      case 'MALE':
        return 'Самец';
      case 'FEMALE':
        return 'Самка';
      default:
        return '-';
    }
  }

  onShow() {
    const dialogRef = this.dialog.open(ModalGallseryComponent, {
      width: '600px',
      data: this.arrayImagesType()
    });
  }

  arrayImagesType() {
    if (this.item.wildAnimalFiles) {
      return this.item.wildAnimalFiles.filter(img => img.photoType.code === 'PHOTO');
    } else {
      return null;
    }
  }
}
