import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {OwnerFileViewComponent} from './owner-file-view.component';

describe('OwnerFileViewComponent', () => {
  let component: OwnerFileViewComponent;
  let fixture: ComponentFixture<OwnerFileViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [OwnerFileViewComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OwnerFileViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
