import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { StoreButtonViewComponent } from './store-button-view.component';

describe('StoreButtonViewComponent', () => {
  let component: StoreButtonViewComponent;
  let fixture: ComponentFixture<StoreButtonViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ StoreButtonViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StoreButtonViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
