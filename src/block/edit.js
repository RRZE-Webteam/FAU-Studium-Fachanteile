import { __ } from "@wordpress/i18n";
import { useState, useEffect } from '@wordpress/element';
import { SelectControl, __experimentalRadio as Radio, __experimentalRadioGroup as RadioGroup, ToggleControl } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import './editor.scss';
import ServerSideRender from "@wordpress/server-side-render";


const Edit = (props) => {

    const { attributes, setAttributes } = props;
    const { selectedDegree, selectedSubject, format, showPercent, showTitle} = attributes;

    // Zustand für die dynamischen Optionen
    const [degree, setDegree] = useState([]);
    const [subject, setSubject] = useState([]);
    const [setFormat] = useState(['chart']);
    const [ setShowPercent ] = useState( true );
    const [ setShowTitle ] = useState( false );

    useEffect(() => {
        if (window.sharesBlockData) {
            setDegree(window.sharesBlockData.degreeOptions || []);
            setSubject(window.sharesBlockData.subjectOptions || []);
        }
    }, []);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <>
                    <SelectControl
                        label={__("Degree", "fau-degree-program-shares")}
                        value={selectedDegree}
                        options={degree.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedDegree: value })}
                    />
                    <SelectControl
                        label={__('Subject', 'fau-degree-program-shares')}
                        value={selectedSubject}
                        options={subject.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedSubject: value })}
                    />
                    <RadioGroup
                        label={__("Format", "fau-degree-program-shares")}
                        onChange={ (value) => {
                            setAttributes({ format: value });
                        } }
                        checked={ format }>
                        <Radio __next40pxDefaultSize value="chart">{__("Chart", "fau-degree-program-shares")}</Radio>
                        <Radio __next40pxDefaultSize value="table">{__("Table", "fau-degree-program-shares")}</Radio>
                    </RadioGroup>
                    {format === "chart" && (
                        <ToggleControl
                            //__nextHasNoMarginBottom
                            label={__("Show Percent Values", "fau-degree-program-shares")}
                            checked={ showPercent }
                            onChange={ (value) => {
                                setAttributes({ showPercent: value });
                            } }
                        />
                    )}
                    <ToggleControl
                        //__nextHasNoMarginBottom
                        label={__("Show Title", "fau-degree-program-shares")}
                        checked={ showTitle }
                        onChange={ (value) => {
                            setAttributes({ showTitle: value });
                        } }
                    />
                </>
            </InspectorControls>
            <ServerSideRender
                block="fau-degree-program/shares"
                attributes={attributes}
            />
        </div>
    );

};

export default Edit;
