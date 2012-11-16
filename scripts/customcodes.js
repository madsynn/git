(function() {  
    tinymce.create('tinymce.plugins.matrix_shortcodes', {
        init : function(ed, url) {
			ed.addButton('vertical_separator', {
                title : 'Add a vertical separator',
                image : url+'/admin/add-vsep.png',
                onclick : function() {
                     ed.selection.setContent('[vertical_separator]');
  
                }
            });
			ed.addButton('quote', {
                title : 'Add a quote box',
                image : url+'/admin/add-quote.png',
                onclick : function() {
                     ed.selection.setContent('[quote][/quote]');
  
                }
            });
			ed.addButton('dropcap', {
                title : 'Create a dropcap',
                image : url+'/admin/add-dropcap.png',
                onclick : function() {
                     ed.selection.setContent('[dropcap][/dropcap]');
  
                }
            });
			
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('matrix_shortcodes', tinymce.plugins.matrix_shortcodes);
	
	tinymce.create('tinymce.plugins.dropdown', {
        createControl : function(n, cm) {
            var t = this, c, ed = t.editor;

            if (n == 'columns') {
                                
                var dd = cm.createListBox('columns', {
                     title : 'Columns',
                     onselect : function(v) {
						 tinyMCE.activeEditor.execCommand('mceInsertContent',false,v);
                     }
                });        

                // Add some values to the list box
                dd.add('One Half', '[one_half title=""][/one_half]');
                dd.add('One Half Last', '[one_half_last title=""][/one_half_last]');
                dd.add('One Third', '[one_third title=""][/one_third]');
                dd.add('One Third Last', '[one_third_last title=""][/one_third_last]');
                dd.add('Two Thirds', '[two_thirds title=""][/two_thirds]');
                dd.add('Two Thirds Last', '[two_thirds_last title=""][/two_thirds_last]');
				dd.add('One Fourth', '[one_fourth title=""][/one_fourth]');
                dd.add('One Fourth Last', '[one_fourth_last title=""][/one_fourth_last]');
				dd.add('Three Fourths', '[three_fourths title=""][/three_fourths]');
                dd.add('Three Fourths Last', '[three_fourths_last title=""][/three_fourths_last]');
                

                // Return the new listbox instance
                return dd;
                
            }
        },
    });
			
	tinymce.create('tinymce.plugins.dropdown2', {
        createControl : function(n, cm) {
            var t = this, c, ed = t.editor;

			if (n == 'buttons') {
                                
                var bt = cm.createListBox('buttons', {
                     title : 'Buttons',
                     onselect : function(v) {
						 tinyMCE.activeEditor.execCommand('mceInsertContent',false,v);
                     }
                });        

                // Add some values to the list box
                bt.add('Theme Colour', '[button color="dark-themecolor" link=""]Text[/button]');
                bt.add('Black', '[button color="dark" link=""]Text[/button]');
                bt.add('Blue', '[button color="dark-blue" link=""]Text[/button]');
                bt.add('Brown', '[button color="dark-brown" link=""]Text[/button]');
                bt.add('Green', '[button color="dark-green" link=""]Text[/button]');
                bt.add('Lime', '[button color="dark-lime" link=""]Text[/button]');
				bt.add('Magenta', '[button color="dark-magenta" link=""]Text[/button]');
                bt.add('Mango', '[button color="dark-mango" link=""]Text[/button]');
				bt.add('Pink', '[button color="dark-pink" link=""]Text[/button]');
                bt.add('Purple', '[button color="dark-purple" link=""]Text[/button]');
				bt.add('Red', '[button color="dark-red" link=""]Text[/button]');
				bt.add('Teal', '[button color="dark-teal" link=""]Text[/button]');
                

                // Return the new listbox instance
                return bt;
                
            }
        },
    });
	
	tinymce.create('tinymce.plugins.dropdown3', {
        createControl : function(n, cm) {
            var t = this, c, ed = t.editor;

			if (n == 'infobox') {
                                
                var bt = cm.createListBox('infobox', {
                     title : 'Infobox',
                     onselect : function(v) {
						 tinyMCE.activeEditor.execCommand('mceInsertContent',false,v);
                     }
                });        

                // Add some values to the list box
                bt.add('Error', '[infobox color="red"]Information[/infobox]');
                bt.add('Warning', '[infobox color="yellow"]Information[/infobox]');
                bt.add('Okay', '[infobox color="green"]Information[/infobox]');
                bt.add('Info', '[infobox color="blue"]Information[/infobox]');                

                // Return the new listbox instance
                return bt;
                
            }
        },
    });
	
	tinymce.create('tinymce.plugins.dropdown4', {
        createControl : function(n, cm) {
            var t = this, c, ed = t.editor;

			if (n == 'phl') {
                                
                var bt = cm.createListBox('phl', {
                     title : 'Highlight',
                     onselect : function(v) {
						 tinyMCE.activeEditor.execCommand('mceInsertContent',false,v);
                     }
                });        

                // Add some values to the list box
                bt.add('Left', '[phl style="1"]Content[/phl]');
                bt.add('All', '[phl style="2"]Content[/phl]');
                bt.add('Right', '[phl style="3"]Content[/phl]');

                // Return the new listbox instance
                return bt;
                
            }
        },
    });

    tinymce.PluginManager.add('dropdown', tinymce.plugins.dropdown);
	tinymce.PluginManager.add('buttons', tinymce.plugins.dropdown2);
	tinymce.PluginManager.add('infobox', tinymce.plugins.dropdown3);
	tinymce.PluginManager.add('phl', tinymce.plugins.dropdown4);
	
	tinymce.create('tinymce.plugins.tabs', {
        init : function(ed, url) {
			ed.addButton('tabgroup', {
                title : 'Add a group of tabs',
                image : url+'/admin/add-tab-group.png',
                onclick : function() {
                     ed.selection.setContent('[tabgroup]<br>[tab title="Tab1"]Tab 1[/tab]<br>[tab title="Tab2"]Tab 2[/tab]<br>[/tabgroup]');
  
                }
            });
			ed.addButton('tabs', {
                title : 'Add a tab',
                image : url+'/admin/add-tab.png',
                onclick : function() {
                     ed.selection.setContent('[tab title="Title"]Content[/tab]');
  
                }
            });
			ed.addButton('table', {
                title : 'Add a table',
                image : url+'/admin/add-table.png',
                onclick : function() {
                     ed.selection.setContent('[table title="Title" price="48"]<br>[row item="Something"]Hover info[/row]<br>[/table]');
  
                }
            });
			ed.addButton('rows', {
                title : 'Add a row to table',
                image : url+'/admin/add-table-row.png',
                onclick : function() {
                     ed.selection.setContent('[row item="Something"][/row]');
  
                }
            });
			ed.addButton('accordion', {
                title : 'Add an accordion group',
                image : url+'/admin/add-accordions.png',
                onclick : function() {
                     ed.selection.setContent('[accordion]<br>[ac_row title="Toggle 1"]Content[/ac_row]<br>[ac_row title="Toggle 2"]Content[/ac_row]<br>[/accordion]');
  
                }
            });
			ed.addButton('ac_row', {
                title : 'Add a new accordion tab',
                image : url+'/admin/add-accordion.png',
                onclick : function() {
                     ed.selection.setContent('[ac_row title="Toggle"]Content[/ac_row]');
  
                }
            });
			ed.addButton('toggles', {
                title : 'Add a new toggle content',
                image : url+'/admin/add-toggles.png',
                onclick : function() {
                     ed.selection.setContent('[toggle title="Toggle" state="open"]Content[/toggle]');
  
                }
            });
			
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);

})();