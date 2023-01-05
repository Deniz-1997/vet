import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {IconAnimalComponent} from './icon-animal.component';

describe('IconComponent', () => {
  let component: IconAnimalComponent;
  let fixture: ComponentFixture<IconAnimalComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [IconAnimalComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IconAnimalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
