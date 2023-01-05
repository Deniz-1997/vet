import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {IconFileTypeComponent} from './icon-file-type.component';

describe('FileTypeIconComponent', () => {
  let component: IconFileTypeComponent;
  let fixture: ComponentFixture<IconFileTypeComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [IconFileTypeComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IconFileTypeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
