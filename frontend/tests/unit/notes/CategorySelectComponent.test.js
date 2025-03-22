import { describe, it, expect, beforeEach } from "vitest";
import { mount } from "../../../node_modules/@vue/test-utils";
import CategorySelectComponent from "../../../src/components/notes/CategorySelectComponent.vue";

describe("CategorySelectComponent", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(CategorySelectComponent, {
      data() {
        return {
          categories: [
            { id: 1, name: "Catégorie A" },
            { id: 2, name: "Catégorie B" },
          ],
          selectedCategory: "",
        };
      },
    });
  });

  it("devrait être monté correctement", () => {
    expect(wrapper.exists()).toBe(true);
  });

  it("devrait afficher les catégories fournies", () => {
    const options = wrapper.findAll("option");
    expect(options.length).toBe(3);
    expect(options[1].text()).toBe("Catégorie A");
    expect(options[2].text()).toBe("Catégorie B");
  });

  it("devrait mettre à jour selectedCategory lorsqu’une option est sélectionnée", async () => {
    const select = wrapper.find("select");
    await select.setValue("1"); 
    expect(wrapper.vm.selectedCategory).toBe(1);
  });

  it('devrait émettre l’événement "category-selected" lors de la sélection', async () => {
    const select = wrapper.find("select");
    await select.setValue("2"); 
    expect(wrapper.emitted("category-selected")).toBeTruthy();
    expect(wrapper.emitted("category-selected")[0]).toEqual([2]);
  });
});
