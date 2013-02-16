 /*Extend tree node to hold extra attributes */

Ext.namespace("Ext.ux.tree")


Ext.ux.tree.TreeNode = Ext.extend(Ext.tree.TreeNode, {
    colour: 'Red',
    constructor: function (cfg) {
        cfg = cfg || {};
        Ext.ux.tree.TreeNode.superclass.constructor.call(this, Ext.apply({
            colour: 'Red'
        }, cfg));
    }
});

Ext.reg('WGPTreeNode', Ext.ux.tree.TreeNode);

    